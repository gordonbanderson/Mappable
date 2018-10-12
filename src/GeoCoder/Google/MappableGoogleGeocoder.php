<?php
namespace WebOfTalent\Mappable\Google;

use Psr\SimpleCache\CacheInterface;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Convert;
use SilverStripe\Core\Injector\Injector;
use WebOfTalent\Mappable\Mappable;
use WebOfTalent\Mappable\MappableGeocoder;

class MappableGoogleGeocoder implements MappableGeocoder
{
    /**
     * Get locations given a search string
     * @param  string $searchString place name to search for, e.g. 'Bangkok'
     * @return array  List of places matching the search term
     */
    public function getLocations($searchString)
    {
        $cache = Injector::inst()->get(CacheInterface::class . '.mappable');

        $cached = false;
        $cacheKey = preg_replace('/[^a-zA-Z0-9_]/', '_', $searchString);

        // reg_replace('/[^a-zA-Z0-9_]/', '_', $basename) . '_' . md5($file);
        $locations = null;

        $apikey = Config::inst()->get(Mappable::class, 'geocoding_service_key');
        error_log('apikey=' . $apikey);

        if (!($json = $cache->get($cacheKey))) {
            $url = "https://maps.googleapis.com/maps/api/geocode/json?sensor=false" .
                "&key=" . $apikey .
                "&address=".
                urlencode($searchString);
            error_log('URL:' . $url);
            if ($json = @file_get_contents(
                $url
            )) {
                $response = Convert::json2array($json);

                if ($response['status'] != 'OK') {
                    if ($response['status'] == 'ZERO_RESULTS') {
                        $locations = array();
                    } else {
                        error_log(print_r($response, 1));
                        throw new \Exception('Google status returned error');
                    }
                } else {
                    $locations = $response['results'];
                }

                // save result in cache
                $cache->set($cacheKey, $json);
            }
        } else {
            $cached = true;
        }

        if ($cached) {
            $response = Convert::json2array($json);
            $locations = $response['results'];
        }
        return $locations;
    }
}
