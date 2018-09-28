<?php
namespace WebOfTalent\Mappable\ShortCode;

use SilverStripe\Core\Config\Config;
use SilverStripe\View\ArrayData;
use SilverStripe\View\Parsers\ShortcodeHandler;
use SilverStripe\View\SSViewer;
use WebOfTalent\Mappable\Mappable;
use WebOfTalent\Mappable\MapUtil;

class GoogleStreetViewShortCodeHandler implements ShortcodeHandler
{
    /* Counter used to ensure unique div ids to allow for multiple StreetViews on on page */
    private static $gsv_ctr = 1;

    /**
     * Gets the list of shortcodes provided by this handler
     *
     * @return mixed
     */
    public static function get_shortcodes()
    {
        return ['googlestreetview'];
    }


    /**
     *
     * @param array $arguments
     * @param string $content
     * @param ShortcodeParser $parser
     * @param string $shortcode
     * @param array $extra
     *
     * @return string
     */
    public static function handle_shortcode($arguments, $content, $parser, $shortcode, $extra = array())
    {
        // each of latitude, longitude and heading are required at a bare minimum
        if (!isset($arguments['latitude'])) {
            return '';
        }

        if (!isset($arguments['longitude'])) {
            return '';
        }

        if (!isset($arguments['heading'])) {
            return '';
        }

        // defaults - these can be overriden by using zoom and pitch in the shortcode
        $defaults = array(
            'Zoom' => 1,
            'Pitch' => 0,
        );

        // provide keys for the first map
        if (!MapUtil::get_map_already_rendered()) {
            $arguments['GoogleMapKey'] = Config::inst()->get(Mappable::class, 'service_key');
            $arguments['GoogleMapLang'] = Config::inst()->get(Mappable::class, 'language');
        }
        MapUtil::set_map_already_rendered(true);

        // convert parameters to CamelCase as per standard template conventions
        $arguments['Latitude'] = $arguments['latitude'];
        $arguments['Longitude'] = $arguments['longitude'];
        $arguments['Heading'] = $arguments['heading'];

        // optional parameter caption
        if (isset($arguments['caption'])) {
            $arguments['Caption'] = $arguments['caption'];
        }

        // optional parameter pitch
        if (isset($arguments['pitch'])) {
            $arguments['Pitch'] = $arguments['pitch'];
        }

        // optional parameter zoom
        if (isset($arguments['zoom'])) {
            $arguments['Zoom'] = $arguments['zoom'];
        }

        // the id of the dom element to be used to render the street view
        $arguments['DomID'] = 'google_streetview_'.self::$gsv_ctr;

        // incrememt the counter to ensure a unique id for each map canvas
        ++self::$gsv_ctr;

        // merge defaults and arguments
        $customised = array_merge($defaults, $arguments);

        // Include google maps JS at the end of the page
        //Requirements::javascriptTemplate("mappable/javascript/google/streetview.google.template.js", $customised);

        //get streetview template template
        $template = new SSViewer('WebOfTalent/Mappable/Includes/GoogleStreetView');

        //return the template customised with the parmameters
        return $template->process(new ArrayData($customised));
    }

    /**
     * This is only used for testing, otherwise the sequence of tests change the number returned.
     */
    public static function resetCounter()
    {
        self::$gsv_ctr = 1;
    }
}
