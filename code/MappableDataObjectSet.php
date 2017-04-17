<?php

/*
 * Provides a GoogleMap() function to DataObject objects.
 *
 * @author Uncle Cheese
 * @package mappable
 */
class MappableDataObjectSet extends Extension
{

    private $RenderMarkers = true;

    public function setRenderMarkers($newRenderMarkers)
    {
        $this->RenderMarkers = $newRenderMarkers;
    }

    public function getRenderableMap($width = null, $height = null)
    {
        $gmap = MapUtil::get_map($this->owner);
        $w = $width ? $width : MapUtil::$map_width;
        $h = $height ? $height : MapUtil::$map_height;
        $gmap->setSize($w, $h);

        if ($this->RenderMarkers == true) {
            foreach ($this->owner->getIterator() as $marker) {
                $gmap->addMarkerAsObject($marker);
            }
        }

        return $gmap;
    }


    public function getMappableCacheKey()
    {
        $key = 'mappabledataobjectset_'.$this->ID.'_'.$this->LastEdited;
        $key .= $this->owner->max('LastEdited');
    }
}
