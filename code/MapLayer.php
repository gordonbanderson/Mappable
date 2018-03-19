<?php
namespace WebOfTalent\Mappable;

use SilverStripe\Assets\File;
use SilverStripe\ORM\DataObject;

class MapLayer extends DataObject
{
    private static $db = array(
        'Title' => 'Varchar(255)',
    );

    private static $has_one = array(
        'KmlFile' => File::class,
    );
}
