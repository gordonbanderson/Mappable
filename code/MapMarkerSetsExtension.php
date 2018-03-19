<?php

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\ORM\DataExtension;

class MapMarkerSetsExtension extends DataExtension
{
    public static $many_many = array(
        'MapMarkerSets' => 'MapMarkerSet',
    );

    public static $belongs_many_many_extraFields = array(
        'MapMarkerSets' => array(
            'SortOrder' => 'Int',
        ),
    );

    public function updateCMSFields(FieldList $fields)
    {
        $gridConfig2 = GridFieldConfig_RelationEditor::create();
        $gridConfig2->getComponentByType(
            GridFieldAddExistingAutocompleter::class)->setSearchFields(array('Title')
        );
        $gridConfig2->getComponentByType(GridFieldPaginator::class)->setItemsPerPage(100);

        $gridField2 = new GridField('MapMarkerSets',
            'MapMarker Sets',
            $this->owner->MapMarkerSets(),
            $gridConfig2
        );
        $fields->addFieldToTab('Root.MapMarkerSets', $gridField2);
    }
}
