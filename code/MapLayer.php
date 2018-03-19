<?php

use SilverStripe\Assets\File;
use SilverStripe\ORM\DataObject;

class MapLayer extends DataObject
{
    public static $db = array(
        'Title' => 'Varchar(255)',
    );

    public static $has_one = array(
        'KmlFile' => File::class,
    );
}
