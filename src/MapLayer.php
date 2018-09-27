<?php
namespace WebOfTalent\Mappable;

use SilverStripe\ORM\DataObject;

class MapLayer extends DataObject
{
    private static $table_name = 'MapLayer';

    private static $db = array(
        'Title' => 'Varchar(255)',
    );

    private static $has_one = array(
        'KmlFile' => 'File',
    );
}
