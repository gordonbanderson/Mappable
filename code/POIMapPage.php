<?php

class POIMapPage extends Page
{
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('Location');
        return $fields;
    }
}

class POIMapPage_Controller extends Page_Controller
{
}
