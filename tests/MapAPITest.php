<?php

class MapAPITest extends SapphireTest {

	public function setUpOnce() {
		$this->requiredExtensions = array(
			'Member' => array('MapExtension')
		);
		parent::setupOnce();
	}

	public function testSetKey() {

	}


	public function testSetIncludeDownloadJavascript() {

	}


	public function testSetShowInlineMapDivStyle() {

	}


	public function testSetAdditionalCSSClasses() {
		$map = $this->getMap();
		$map->setAdditionalCSSClasses('bigMap shadowMap');
		$html = $map->forTemplate();
		$expected = 'class="bigMap shadowMap mappable"';
		$this->assertContains($expected, $html);
	}


	public function testSetMapStyle() {

	}


	public function testSetDelayLoadMapFunction() {

	}


	public function testSetDivId() {
		$map = $this->getMap();
		$map->setDivId('mymapid');
		$html = $map->forTemplate();
		$expected = '<div id="mymapid" style=';
		$this->assertContains($expected, $html);
	}


	public function testSetDirectionDivId() {

	}


	public function testSetSize() {
		$map = $this->getMap();
		$map->setSize('432px','1234px');
		$html = $map->forTemplate();
		$this->assertContains('style="width:432px; height: 1234px;"', $html);
	}


	public function testSetIconSize() {

	}


	public function testSetLang() {
		$map = $this->getMap();
		$map->setLang('fr');
		$html = $map->forTemplate();
		$this->assertContains('style="width:432px; height: 1234px;"', $html);
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


	public function testSetEnableAutomaticCenterZoom() {
		$map = $this->getMap();
		$map->setLang('fr');
		$html = $map->forTemplate();
		$this->assertContains('style="width:432px; height: 1234px;"', $html);
	}


	public function testSetCenter() {
		$map = $this->getMap();
		$map->setIncludeDownloadJavascript(true);
		$map->setCenter(-23.714,47.149);
		$html = $map->forTemplate();
		echo $html;
		$expected = "data-centre='{\"lat\":023.714,\"lng\":47.149}'";
		$this->assertContains($expected, $html);
	}


	public function testSetLatLongCenter() {
		$map = $this->getMap();
		$map->setIncludeDownloadJavascript(true);
		$map->setLatLongCenter(-23.714,47.149);
		$html = $map->forTemplate();
		echo $html;
		$expected = "data-centre='{\"lat\":023.714,\"lng\":47.149}'";
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
		echo $html;
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


	public function testSetDisplayDirectionFields() {
		$map = $this->getMap();
		$map->setDisplayDirectionFields(false);
		$html = $map->forTemplate();
		echo $html;

		$map->setDisplayDirectionFields(true);
		$html = $map->forTemplate();
		echo $html;
		$this->fail('Does not appear to be used');
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


	public function testgetGoogleMap() {

	}


	public function testgetContent() {

	}


	public function testgeocoding() {

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


	public function testaddDirection() {

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
