<?php

use SilverStripe\View\Parsers\ShortcodeParser;

//define global path to Components' root folder
if(!defined('MAPPABLE_MODULE_PATH'))
{
	define('MAPPABLE_MODULE_PATH', rtrim(basename(dirname(__FILE__))));
}

ShortcodeParser::get('default')->register('GoogleStreetView',
    array('WebOfTalent\Mappable\ShortCodes\GoogleStreetViewShortCodeHandler','handle_shortcode'));
ShortcodeParser::get('default')->register('GoogleMap',
    array('WebOfTalent\Mappable\ShortCodes\GoogleMapShortCodeHandler','handle_shortcode'));

//@todo Caching
