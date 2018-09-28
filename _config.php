<?php

use SilverStripe\View\Parsers\ShortcodeParser;
ShortcodeParser::get('default')->register('GoogleStreetView',[\WebOfTalent\Mappable\ShortCode\GoogleStreetViewShortCodeHandler::class,'handle_shortcode']);
ShortcodeParser::get('default')->register('GoogleMap',[\WebOfTalent\Mappable\ShortCode\GoogleMapShortCodeHandler::class,'handle_shortcode']);
