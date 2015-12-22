<?php

//define global path to Components' root folder
if(!defined('MAPPABLE_MODULE_PATH'))
{
	define('MAPPABLE_MODULE_PATH', rtrim(basename(dirname(__FILE__))));
}

ShortcodeParser::get('default')->register('GoogleStreetView',array('GoogleStreetViewShortCodeHandler','parse_googlestreetview'));
ShortcodeParser::get('default')->register('GoogleMap',array('GoogleMapShortCodeHandler','parse_googlemap'));

// recommended update time is 20 mins so play safe and go with 30.
SS_Cache::set_cache_lifetime('mappablegeocoder', 30*60);
