<?php

class MapUtil
{

	/**
	 * @var string The Google Maps API key
	 */
	protected static $api_key;

	/**
	 * @var int Number of active {@see GoogleMapsAPI} instances (for the HTML ID)
	 */
	protected static $instances = 0;

	/**
	 * @var int The default width of a Google Map
	 */
	public static $map_width = '100%';

	/**
	 * @var int The default height of a Google Map
	 */
	public static $map_height = '400px';

	/** @var int Icon width of the gmarker **/
	public static $iconWidth = 24;

	/** @var int Icon height of the gmarker **/
	public static $iconHeight = 24;

	/**
	 * @var int Prefix for the div ID of the map
	 */
	public static $div_id = "google_map";

	/**
	 * @var boolean Automatic center/zoom for the map
	 */
	public static $automatic_center = true;

	/**
	 * @var boolean Show the marker fields on the map
	 */
	public static $hide_marker = false;




	/**
	 * @var boolean Show the marker fields on the map
	 */
	public static $map_type = 'google.maps.MapTypeId.ROADMAP';

	/**
	 * @var string $center Center of map (adress)
	 */
	public static $center = 'Paris, France';

	/* Width of the map information window */
	public static $info_window_width = 250;

	/* Signals whether at least one map has already been rendered */
	private static $map_already_rendered = false;

	/* Whether or not to allow full screen */
	private static $allow_full_screen = null;


	public static function reset() {
		self::$api_key = null;
		self::$instances = 0;
		self::$map_width = '100%';
		self::$map_height = '400px';
		self::$iconWidth = 24;
		self::$iconHeight = 24;
		self::$div_id = "google_map";
		self::$automatic_center = true;
		self::$hide_marker = false;
		self::$map_type = 'google.maps.MapTypeId.ROADMAP';
		self::$center = 'Paris, France';
		self::$info_window_width = 250;
		self::$map_already_rendered = false;
		self::$allow_full_screen = null;
	}

	/**
	 * Set the API key for Google Maps
	 *
	 * @param string $key
	 */
	public static function set_api_key($key) {
		self::$api_key = $key;
	}


	/**
	 * @param boolean $new_map_already_rendered
	 */
	public static function set_map_already_rendered($new_map_already_rendered) {
		self::$map_already_rendered = $new_map_already_rendered;
	}

	public static function get_map_already_rendered() {
		return self::$map_already_rendered;
	}

	/**
	 * Set the default size of the map
	 *
	 * @param int $width
	 * @param int $height
	 */
	public static function set_map_size($width, $height) {
		self:: $map_width = $width;
		self::$map_height = $height;
	}

	/**
	 * FIXME - NOT USED?
	 * Set the type of the gmap
	 *
	 * @param string $mapType (can be 'google.maps.MapTypeId.ROADMAP', 'G_SATELLITE_MAP',
	 * 'G_HYBRID_MAP', 'G_PHYSICAL_MAP')
	 *
	 * @return void
	 */
	public static function set_map_type($mapType) {
		self::$map_type = $mapType;
	}

	/**
	 * FIXME - NOT USED?
	 * Set the with of the gmap infowindow (on marker clik)
	 *
	 * @param int $info_window_width GoogleMap info window width
	 *
	 * @return void
	 */
	public static function set_info_window_width($info_window_width)
	{
		self::$info_window_width = $info_window_width;
	}

	/**
	 * FIXME - NOT USED?
	 * Set the center of the gmap (an address)
	 *
	 * @param string $center GoogleMap  center (an address)
	 *
	 * @return void
	 */
	public static function set_center($center)
	{
		self::$center = $center;
	}

	/**
	 * FIXME Is this used?
	 *
	 * Set the size of the icon markers
	 *
	 * @param int $iconWidth GoogleMap  marker icon width
	 * @param int $iconHeight GoogleMap  marker icon height
	 *
	 * @return void
	 */

	public static function set_icon_size($iconWidth, $iconHeight)
	{
		self::$iconWidth = $iconWidth;
		self::$iconHeight = $iconHeight;
	}

	/**
	 * Get a new GoogleMapAPI object and load it with the default settings
	 *
	 * @return MapAPI
	 */
	public static function instance()
	{
		self::$instances++;

		if (self::$allow_full_screen == null) {
			self::$allow_full_screen = Config::inst()->get('Mappable', 'allow_full_screen');
		}

		$url = Director::absoluteBaseURL();

		// remove http and https
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);
		$parts = explode('/', $url);
		$host = $parts[0];

		$key = self::$api_key;

		// if an array, get the key by an array keyed by host
		if (is_array($key)) {
			$key = $key[$host];
		}


		$gmap = new MapAPI($key);
		$gmap->setDivId(self::$div_id."_".self::$instances);
		$gmap->setEnableAutomaticCenterZoom(self::$automatic_center);
		$gmap->setSize(self::$map_width, self::$map_height);
		$gmap->setDefaultHideMarker(self::$hide_marker);
		$gmap->setMapType(self::$map_type);
		$gmap->setCenter(self::$center);
		$gmap->setIconSize(self::$iconWidth, self::$iconHeight);
		$gmap->setIncludeDownloadJavascript(self::$map_already_rendered);
		$gmap->setAllowFullScreen(self::$allow_full_screen);
		return $gmap;
	}


	/**
	 * Sanitize a string of HTML content for safe inclusion in the JavaScript
	 * for a Google Map
	 *
	 * @return string
	 */
	public static function sanitize($content) {
		return addslashes(str_replace(array("\n", "\r", "\t"), '', $content));
	}


	/**
	 * Creates a new {@link GoogleMapsAPI} object loaded with the default settings
	 * and places all of the items in a {@link SS_List}
	 * e.g. {@link DataList} or {@link ArrayList} on the map
	 *
	 * @param SS_List list of objects to display on a map
	 * @param  array $infowindowtemplateparams Optional array of extra parameters to pass to the map info window
	 * @return MapAPI
	 */
	public static function get_map(SS_List $list, $infowindowtemplateparams) {
		$gmap = self::instance();
		if ($list) {
			foreach ($list as $mappable) {
				if (self::ChooseToAddDataobject($mappable))
					$gmap->addMarkerAsObject($mappable, $infowindowtemplateparams);
			}
		}
		return $gmap;
	}

	/**
	 * Determines if the current DataObject should be included to the map
	 * Checks if it has Mappable interface implemented
	 * If it has MapExtension included, the value of MapPinEdited is also checked
	 *
	 * @param DataObject $do
	 * @return bool
	 */
	private static function ChooseToAddDataobject(DataObject $do) {
		$isMappable = $do->is_a('Mappable');

		foreach ($do->getExtensionInstances() as $extension) {
			$isMappable = $isMappable || $extension instanceof Mappable;
		}

		$filterMapPinEdited = $do->hasExtension('MapExtension')
			? $do->MapPinEdited
			: true;

		return $isMappable && $filterMapPinEdited;
	}
}
