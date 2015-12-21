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


	public function testforTemplate() {

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


	public function testgenerate() {

	}


	public function testprocessTemplateJS() {

	}


	public function testprocessTemplateHTML() {

	}

	private function getMap() {
		$instance = new Member();
		return $instance->getRenderableMap();
	}

}
