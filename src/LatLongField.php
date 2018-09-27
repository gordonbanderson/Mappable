<?php
namespace WebOfTalent\Mappable;

use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FormField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\View\Requirements;
use  SilverStripe\View\HTML;

class LatLongField extends FieldGroup
{
    const RESOURCES_PATH='resources/silverstripe/admin/thirdparty';

    protected $latField;

    protected $longField;

    protected $zoomField;

    protected $buttonText;

    private $guidePoints = null;

    private static $ctr = 0;

    /**
     * @param string[] $buttonText
     */
    public function __construct($children = array(), $buttonText = null)
    {
        ++self::$ctr;

        if ((sizeof($children) < 2) || (sizeof($children) > 3) ||
             (!$children[0] instanceof FormField) ||
             (!$children[1] instanceof FormField)
        ) {
            user_error('LatLongField argument 1 must be an array containing at least two FormField '.
                'objects for Lat/Long values, respectively.', E_USER_ERROR);
        }

        parent::__construct($children);

        $this->buttonText = $buttonText ? $buttonText : _t('LatLongField.LOOKUP', 'Search');
        $this->latField = $children[0]->getName();
        $this->longField = $children[1]->getName();
        $this->setLegend('Legend');

        if (sizeof($children) == 3) {
            $this->zoomField = $children[2]->getName();
        }
        $name = '';
        foreach ($children as $field) {
            $name .= $field->getName();
        }

        // hide the lat long and zoom fields from the interface
        foreach ($this->FieldList() as $fieldToHide) {
            $fieldToHide->addExtraClass('hide');
        }

        $this->name = $name;
    }

    public function FieldHolder($properties = [])
    {
        error_log('FIELD HOLDER LAT LONG FIELD');
        error_log('LLF T1');
        if (self::$ctr == 1) {
            // "todo Review this, may not be required.  Also class is not namespaced
            if(!$apikey = Config::inst()->get(Mappable::class, 'service_key')){
                error_log('LLF T2');
                //Requirements::javascript('weboftalent/mappable:javascript/mapField.js');
                $apikey = 0;
            }

            error_log('LLF T3');

            $vars = ['MapsApiKey' => $apikey];
            Requirements::javascriptTemplate('weboftalent/mappable:/admin/client/src/maps-api-key.ss.js', $vars);
        }

        error_log('LLF T4');

       // Requirements::javascript(self::RESOURCES_PATH.'/jquery-livequery/jquery.livequery.js');
       // Requirements::javascript(self::RESOURCES_PATH.'/jquery-metadata/jquery.metadata.js');
        Requirements::javascript('weboftalent/mappable: dist/js/mapfield.js');

        $attributes = array(
            'class' => 'editableMap',
            'id' => 'GoogleMap',
            'data-LatFieldName' => $this->latField,
            'data-LonFieldName' => $this->longField,
            'data-ZoomFieldName' => $this->zoomField,
            'data-UseMapBounds' => false,
        );

        Requirements::css('weboftalent/mappable: dist/css/adminclientbundle.css');



        // check for and if required add guide points
        if (!empty($this->guidePoints)) {
            $latlongps = array();

            foreach ($this->guidePoints as $guidepoint) {
                array_push($latlongps, $guidepoint);
            }

            $guidePointsJSON = json_encode($latlongps);
            // convert the mappable guidepoints to lat lon

            $attributes['data-GuidePoints'] = $guidePointsJSON;

            // we only wish to change the bounds to those of all the points iff
            // the item currently has no location
            $attributes['data-useMapBounds'] = true;
        }

        $this->addExtraClass('editableMap');
        $this->setAttribute('id', 'GoogleMap');
        
        $map = '<div class="editableMapWrapper">'.HTML::createTag(
                'div',
                $attributes
            ).'</div>';



        $content = '<div id="Form_EditForm_MappableMap_Holder" class="form-group field text">
            <label for="Form_EditForm_MappableMap_Holder" class="form__field-label">Map</label>
            <div class="form__field-holder input-group">
                ' . $map . '
            </div>
        </div>';

       $this->FieldList()->push(new LiteralField('locationEditor', $content));

        /*
         * <div id="Form_EditForm_Title_Holder" class="form-group field text">

        <label for="Form_EditForm_Title" id="title-Form_EditForm_Title" class="form__field-label">Page name</label>

    <div class="form__field-holder input-group">
        <input name="Title" value="Contact Us" class="text" id="Form_EditForm_Title" type="text"><button class="update btn btn-outline-secondary form__field-update-url" type="button" style="display: none;">Update URL</button>



    </div>

</div>
         */

        $content2 = <<<HTML
        <div id="mapSearch" class="form-group field text">

            <label for="mapSearch" class="form__field-label">Search</label>
    
            <div class="form__field-holder input-group">
                <input name="location_search" value="Contact Us" class="text" id="location_search" type="text">
                <button class="update btn btn-outline-secondary form__field-update-url"  id="searchLocationButton" type="button">Search Location Name</button>
                <div id="mapSearchResults"></div>
            </div>
        </div>

HTML;



        $this->FieldList()->push(new LiteralField('mapSearch', $content2));

        return parent::FieldHolder();
    }

    /*
    Set guidance points for the map being edited.  For example in a photographic set show the map
    position of some other images so that subsequent photo edits do not start with a map centred
    at the origin

    @var newGuidePoints array of points expressed as associative arrays containing keys latitude
                        and longitude mapping to geographical locations
    */
    public function setGuidePoints($newGuidePoints)
    {
        $this->guidePoints = $newGuidePoints;
    }

    /**
     * Accessor to guidepoints.  For testing purposes.
     *
     * @return array guidepoints
     */
    public function getGuidePoints()
    {
        return $this->guidePoints;
    }
}
