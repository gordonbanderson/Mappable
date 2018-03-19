<?php
namespace WebOfTalent\Mappable;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\ORM\DataExtension;

class MapLayerExtension extends DataExtension
{
    public static $many_many = array(
            'MapLayers' => 'MapLayer',
    );

    public static $belongs_many_many_extraFields = array(
        'MapLayers' => array(
            'SortOrder' => 'Int',
        ),
    );

    public function updateCMSFields(FieldList $fields)
    {
        $gridConfig2 = GridFieldConfig_RelationEditor::create();
        $gridConfig2->getComponentByType(
            GridFieldAddExistingAutocompleter::class
        )->
            setSearchFields(array('Title'));
        $gridConfig2->getComponentByType(GridFieldPaginator::class)->setItemsPerPage(100);
        $gridField2 = new GridField(
            'Map Layers',
            'Map Layers:',
            $this->owner->MapLayers(),
            $gridConfig2
        );
        $fields->addFieldToTab('Root.MapLayers', $gridField2);
    }

    /**
     * Only set has geo to true if layers exist
     * @param  boolean &$hasGeo will be set to true if any layers
     */
    public function updateHasGeo(&$hasGeo)
    {
        if ($this->owner->MapLayers()->count() > 0) {
            $hasGeo = true;
        }
    }

    /**
     * Add layers if the exist to a map from the MapExtension
     * @param  MapAPI &$map      object representing the map
     * @param  boolean &$autozoom true to auto zoom, false not to
     */
    public function updateBasicMap(&$map, &$autozoom)
    {
        // add any KML map layers
        foreach ($this->owner->MapLayers() as $layer) {
            $map->addKML($layer->KmlFile()->getAbsoluteURL());
            // we have a layer, so turn on autozoom
            $autozoom = true;
        }
    }
}
