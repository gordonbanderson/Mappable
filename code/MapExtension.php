<?php

class MapExtension extends DataExtension implements Mappable {

	/*
	 * Template suffix for rendering MapInfoWindow aka  map bubble
	 */
	private static $map_info_window_suffix = '_MapInfoWindow';

	private static $db = array(
		'Lat' => 'Decimal(18,15)',
		'Lon' => 'Decimal(18,15)',
		'ZoomLevel' => 'Int',
		'MapPinEdited' => 'Boolean'
	);

	static $has_one = array(
		'MapPinIcon' => 'Image'
	);

	static $defaults = array (
		'Lat' =>0,
		'Lon' => 0,
		'Zoom' => 4,
		'MapPinEdited' => false
	);


	/*
	Add a Location tab containing the map
	*/
	public function updateCMSFields(FieldList $fields) {
		// These fields need removed, as they may have already been created by the form scaffolding
		$fields->removeByName('Lat');
		$fields->removeByName('Lon');
		$fields->removeByName('ZoomLevel');
		$fields->removeByName('MapPinIcon');
		$fields->removeByName('MapPinEdited');

		$fields->addFieldToTab("Root.Location",
			$this->getMapField()
		);

		$fields->addFieldToTab('Root.Location', $uf = new UploadField('MapPinIcon',
			_t('Mappable.MAP_PIN', 'Map Pin Icon.  Leave this blank for default pin to show')));
		$uf->setFolderName('mapicons');
	}


	public function getMappableLatitude() {
		return $this->owner->Lat;
	}

	public function getMappableLongitude() {
		return $this->owner->Lon;
	}

	/**
	 * Renders the map info window for the DataObject.
	 *
	 * Be sure to define a template for that, named by the decorated class suffixed with _MapInfoWindow
	 * e.g. MyPage_MapInfoWindow
	 *
	 * You can change the suffix globally by editing the MapExtension.map_info_window_suffix config val
	 *
	 * @return string
	 */
	public function getMappableMapContent() {
		$defaultTemplate = 'MapInfoWindow';
		$classTemplate =
			SSViewer::get_templates_by_class(
				$this->owner->ClassName,
				Config::inst()->get('MapExtension', 'map_info_window_suffix')
			);
		$template = count($classTemplate) ? $classTemplate : $defaultTemplate;
		return MapUtil::sanitize($this->owner->renderWith($template));
	}

	/*
	If the marker pin is not at position 0,0 mark the pin as edited. This provides the option of
	filtering out (0,0) point which is often irrelevant for plots
	*/
	public function onBeforeWrite() {
		if (($this->owner->Lat !== 0) || ($this->owner->Lon !== 0)) {
		  $this->owner->MapPinEdited = true;
		}
	}

	/*
	If a user has uploaded a map pin icon display that, otherwise
	*/
	public function getMappableMapPin() {
		$result = false;
		if ($this->owner->MapPinIconID != 0) {
			$mappin = $this->owner->MapPinIcon();
			$result = $mappin->getAbsoluteURL();
		} else {
		  // check for a cached map pin already having been provided
			if ($this->owner->CachedMapPin) {
				$result = $this->owner->CachedMapPin;
		  	}
		}
		return $result;
	}

	/*
		Check for non zero coordinates, on the assumption that (0,0) will never be the desired coordinates
	*/
	public function HasGeo() {
		$result = ($this->owner->Lat != 0) && ($this->owner->Lon != 0);
		if ($this->owner->hasExtension('MapLayerExtension')) {
			if ($this->owner->MapLayers()->count() > 0) {
				$result = true;
			}
		}

		if ($this->owner->hasExtension('PointsOfInterestLayerExtension')) {
			if ($this->owner->PointsOfInterestLayers()->count() > 0) {
				$result = true;
			}
		}
		return $result;
	}




	/*
	Render a map at the provided lat,lon, zoom from the editing functions,
	*/
	public function BasicMap() {
		$map = $this->owner->getRenderableMap();
		$map->setZoom($this->owner->ZoomLevel);
		$map->setAdditionalCSSClasses('fullWidthMap');
		$map->setShowInlineMapDivStyle(true);

		// add any KML map layers
		if (Object::has_extension($this->owner->ClassName, 'MapLayerExtension')) {
		  foreach($this->owner->MapLayers() as $layer) {
			$map->addKML($layer->KmlFile()->getAbsoluteURL());
		  }
			$map->setEnableAutomaticCenterZoom(true);
		}


		// add points of interest taking into account the default icon of the layer as an override
		if (Object::has_extension($this->owner->ClassName, 'PointsOfInterestLayerExtension')) {
			$markercache = SS_Cache::factory('mappable');

			$ck = $this->getPoiMarkersCacheKey();
			$map->MarkersCacheKey = $ck;

			// If we have JSON already do not load the objects
			if (!($jsonMarkers = $markercache->test($ck)))	{
				foreach($this->owner->PointsOfInterestLayers() as $layer) {
					$layericon = $layer->DefaultIcon();
					if ($layericon->ID === 0) {
						$layericon = null;
					}
					foreach ($layer->PointsOfInterest() as $poi) {
						if ($poi->MapPinEdited) {
							if ($poi->MapPinIconID == 0) {
								$poi->CachedMapPin = $layericon;
							}
							$map->addMarkerAsObject($poi);
						}
					}
				}
			}
		}

		$map->setClusterer(true);
		$map->setEnableAutomaticCenterZoom(true);

		$map->setZoom(10);
		$map->setAdditionalCSSClasses('fullWidthMap');
		$map->setShowInlineMapDivStyle(true);
		$map->setClusterer(true);

		return $map;
	}


	/**
	 * Access the map editing field for the purpose of adding guide points
	 * @return [LatLongField] instance of location editing field
	 */
	public function getMapField() {
		if (!isset($this->mapField)) {
			$this->mapField = new LatLongField(array(
				new TextField('Lat', 'Latitude'),
				new TextField('Lon', 'Longitude'),
				new TextField('ZoomLevel', 'Zoom')
				),
				array('Address')
			);
		}
		return $this->mapField;
	}


	/**
	 * Template helper, used to decide whether or not to use compressed assets
	 */
	public function UseCompressedAssets() {
		return Config::inst()->get('Mappable', 'use_compressed_assets');
	}


	/**
	 * Obtain a cache key for markers based on their last edited values
	 * @return [String] Cache key that is unique if a marker is changed.
	 */
	private function getPoiMarkersCacheKey() {
		$sql = <<<SQL
SELECT MAX(poi.LastEdited) as POILastEdited,MAX(poil.LastEdited) as POILayerLastEdited
FROM PointOfInterest poi
INNER JOIN PointsOfInterestLayer_PointsOfInterest poilpoi
ON poi.ID = poilpoi.PointOfInterestID
INNER JOIN PointsOfInterestLayer poil
ON poil.ID = poilpoi.PointsOfInterestLayerID
;
SQL;
		$lasteditedvals = DB::query($sql)->first();
		$key = 'poimarkers_'.$this->owner->ID.'_'.$this->owner->LastEdited.'_';
		$key .= $lasteditedvals['POILastEdited'].'__'.$lasteditedvals['POILayerLastEdited'];
		//$key = str_replace('-', '_', $key);
		//$key = str_replace(':', '_', $key);
		//$key = rand();
		return hash('ripemd160',$key);
	}
}
