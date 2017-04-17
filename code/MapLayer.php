<?php

class MapLayer extends DataObject
{

    public static $db = array(
        'Title' => 'Varchar(255)'
    );


    public static $has_one = array(
        'KmlFile' => 'File'
    );

    public function getCMSFields_forPopup()
    {
        $fields = new FieldSet();

        $fields->push(new TextField('Title'));
        $fields->push(new FileIFrameField('KmlFile'));

        return $fields;
    }
}
