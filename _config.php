<?php

use SilverStripe\View\Parsers\ShortcodeParser;
ShortcodeParser::get('default')->register('googlestreetview',\WebOfTalent\Mappable\ShortCode\GoogleStreetViewShortCodeHandler::class,'handle_shortcode');
ShortcodeParser::get('default')->register('googlemap',\WebOfTalent\Mappable\ShortCode\GoogleMapShortCodeHandler::class,'handle_shortcode');
