<?php

class MappableGoogleGeocoder implements MappableGeocoder {
	/**
	 * Get locations given a search string
	 * @param  string $searchString place name to search for, e.g. 'Bangkok'
	 * @return array  List of places matching the search term
	 */
	public function getLocations($searchString) {
		$cache = SS_Cache::factory('mappablegeocoder');
		$cached = false;
		$cacheKey = preg_replace('/[^a-zA-Z0-9_]/', '_', $searchString);

		// reg_replace('/[^a-zA-Z0-9_]/', '_', $basename) . '_' . md5($file);
		$locations = null;

		if (!($json = $cache->load($cacheKey))) {
			error_log('GEOCODE NOT CACHED');
			if ($json = @file_get_contents(
				"http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=".
				urlencode($searchString))) {
				$response = Convert::json2array($json);

				if ($response['status'] != 'OK') {
					throw new MappableGeocoderException('Google status returned error');
				}
				$locations = $response['results'];
				$cache->save($json, $cacheKey);


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
