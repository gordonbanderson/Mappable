<?php

class MapAPITest extends SapphireTest {

	public function setUpOnce() {
		$this->requiredExtensions = array(
			'Member' => array('MapExtension')
		);
		parent::setupOnce();
	}


	public function setUp() {
		MapUtil::reset();
		parent::setUp();
	}

	public function testSetKey() {
		$map = $this->getMap();
		$map->setKey('PRETEND_KEY');
		$html = $map->forTemplate();
		$map->setKey(null);

		$this->fail('where to check effect?');
	}


	public function testSetIncludeDownloadJavascript() {
		$map = $this->getMap();
		$map->setIncludeDownloadJavascript(true);
		$html = $map->forTemplate();
		$map->setIncludeDownloadJavascript(false);

		$this->fail('where to check effect?');
	}


	public function testSetShowInlineMapDivStyle() {

	}


	public function testSetAdditionalCSSClasses() {
		$map = $this->getMap();
		$map->setAdditionalCSSClasses('bigMap shadowMap');
		$html = $map->forTemplate();
		$expected = 'class="bigMap shadowMap mappable"';
		$this->assertContains($expected, $html);
		$map->setAdditionalCSSClasses('bigMap shadowMap');
	}


	public function testSetMapStyle() {
		$style = <<<STYLE
[{
	"featureType": "landscape",
	"stylers": [{
		"hue": "#FFBB00"
	}, {
		"saturation": 43.400000000000006
	}, {
		"lightness": 37.599999999999994
	}, {
		"gamma": 1
	}]
}]
STYLE;
		$map = $this->getMap();
		$map->setMapStyle($style);
		$html = $map->forTemplate()->getValue();
		$expected = <<<HTML


<div id="google_map_1" style="width:100%; height: 400px;"
 class=" mappable"
data-map
data-centre='{"lat":48.856614,"lng":2.3522219}'
data-zoom=9
data-maptype='road'
data-allowfullscreen='1'
data-clusterergridsize=50,
data-clusterermaxzoom=17,
data-enableautocentrezoom=false
data-mapmarkers='[]'
data-lines='[]'
data-kmlfiles='[]'
data-mapstyles='[{
	"featureType": "landscape",
	"stylers": [{
		"hue": "#FFBB00"
	}, {
		"saturation": 43.400000000000006
	}, {
		"lightness": 37.599999999999994
	}, {
		"gamma": 1
	}]
}]'
data-useclusterer=false
>
</div>

HTML;
		$this->assertEquals($expected, $html);
		$map->setMapStyle(null);
	}


	public function testSetDelayLoadMapFunction() {
		$this->fail('Not clear if this is still used');
	}


	public function testSetDivId() {
		$map = $this->getMap();
		$map->setDivId('mymapid');
		$html = $map->forTemplate();
		$expected = '<div id="mymapid" style=';
		$this->assertContains($expected, $html);
	}

	public function testSetSize() {
		$map = $this->getMap();
		$map->setSize('432px', '1234px');
		$html = $map->forTemplate();
		$this->assertContains('style="width:432px; height: 1234px;"', $html);
	}

	public function testSetLang() {
		$map = $this->getMap();
		$map->setLang('fr');
		$html = $map->forTemplate();
		$this->fail('Response needs checked');
	}


	public function testSetZoom() {
		$map = $this->getMap();
		$map->setZoom(4);
		$html = $map->forTemplate();
		$this->assertContains('data-zoom=4', $html);
		$map->setZoom(12);
		$html = $map->forTemplate();
		$this->assertContains('data-zoom=12', $html);
	}


	public function testSetInfoWindowZoom() {

	}


	public function testSetEnableWindowZoom() {

	}

	public function testSetIconSize() {

	}



	public function testSetEnableAutomaticCenterZoom() {
		$map = $this->getMap();
		$map->setEnableAutomaticCenterZoom(true);
		$html = $map->forTemplate();
		$this->assertContains('data-enableautocentrezoom=1', $html);
	}


	/**
	 * setCentre is mis-named, as the method expects text for a geocoder
	 */
	public function testSetCenter() {
		$map = $this->getMap();
		$map->setIncludeDownloadJavascript(true);
		$map->setCenter('Klong Tan, Bangkok, Thailand');
		$html = $map->forTemplate();

		//coordinates of Klong Tan in Bangkok
		$expected = 'data-centre=\'{"lat":13.7243075,"lng":100.5718086}';
		$this->assertContains($expected, $html);
		$map->setCenter('Paris, France');
	}


	public function testSetLatLongCenter() {
		$map = $this->getMap();
		$map->setIncludeDownloadJavascript(true);
		$llc = array('lat' => -23.714, 'lng' => 47.419);
		$map->setLatLongCenter($llc);
		$html = $map->forTemplate();
		$expected = "data-centre='{\"lat\":-23.714,\"lng\":47.419}'";
		$this->assertContains($expected, $html);
	}


	public function testSetMapType() {
		$map = $this->getMap();

		$mapTypes = array(
			'road' => 'road',
			'satellite' => 'satellite',
			'hybrid' => 'hybrid',
			'terrain' => 'terrain',
			'google.maps.MapTypeId.ROADMAP' => 'road',
			'google.maps.MapTypeId.SATELLITE' => 'satellite',
			'google.maps.MapTypeId.G_HYBRID_MAP' => 'hybrid',
			'google.maps.MapTypeId.G_PHYSICAL_MAP' => 'terrain',
			'unrecognised_name' => 'road'

		);

		foreach ($mapTypes as $mapType) {
			$map->setMapType($mapType);
			$expected = "data-maptype='".$mapTypes[$mapType]."'";
			$html = $map->forTemplate();
			$this->assertContains($expected, $html);
		}
	}


	public function testSetAllowFullScreen() {
		$map = $this->getMap();
		$map->setAllowFullScreen(false);
		$html = $map->forTemplate();

		//FIXME this is possibly problematic
		$this->assertContains("data-allowfullscreen='false'", $html);

		$map->setAllowFullScreen(true);
		$html = $map->forTemplate();
		$this->assertContains("data-allowfullscreen='1'", $html);
	}

	public function testMapWithMarkers() {
		$config = Config::inst();

		$map = $this->getMapMultipleItems();
		$html = $map->forTemplate();
		$expected = 'data-mapmarkers=\'[{"latitude":23,"longitude":78,"html":"'
				  . 'MEMBER: Fred Bloggs","category":"default","icon":false},{"latitude'
				  . '":-12,"longitude":42.1,"html":"MEMBER: Kane Williamson","category"'
				  . ':"default","icon":false}]\'';
		$this->assertContains($expected, $html);
	}


	public function testMapWithMarkersDifferentCategory() {
		$this->markTestSkipped('TODO');
	}


	public function testSetDefaultHideMarker() {

	}


	public function testGetGoogleMap() {

	}


	public function testgetContent() {

	}


	public function testGeocoding() {
		$map = $this->getMap();
		$location = $map->geocoding("Nonthaburi, Thailand");
		$expected = array(
			'lat' => 13.8621125,
			'lon' => 100.5143528,
    		'geocoded' => true
		);
		$this->assertEquals($expected, $location);
	}

	public function testaddMarkerByCoords() {

	}


	public function testaddMarkerByAddress() {

	}


	public function testaddArrayMarkerByCoords() {

	}


	public function testaddMarkerAsObject() {

	}


	public function testconnectPoints() {

	}


	public function testaddArrayMarkerByAddress() {

	}

	public function testaddKML() {

	}


	public function testaddLine() {

	}


	public function testjsonRemoveUnicodeSequences() {

	}


	public function testprocessTemplateJS() {

	}


	public function testprocessTemplateHTML() {

	}




	private function getMap() {
		$instance = new Member();
		return $instance->getRenderableMap();
	}

	private function getMapMultipleItems() {
		$members = new ArrayList();

		$member1 = new Member();
		$member1->Lat = 23;
		$member1->Lon = 78;
		$member1->MapPinEdited = true;
		$member1->FirstName = 'Fred';
		$member1->Surname = 'Bloggs';
		$members->push($member1);

		$member2 = new Member();
		$member2->Lat = -12;
		$member2->Lon = 42.1;
		$member2->MapPinEdited = true;
		$member2->FirstName = 'Kane';
		$member2->Surname = 'Williamson';
		$members->push($member2);

		return $members->getRenderableMap();
	}

}
