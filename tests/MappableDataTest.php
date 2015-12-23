<?php

class MappableDataTest extends SapphireTest {

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

	public function testGetRenderableMapSetNoMarkerValues() {
		$instance = $this->getInstance();
		$instance->MapPinEdited = true;
		$html = $instance->getRenderableMap(300, 800, 2)->setDivId('testmap')->forTemplate()->getValue();


		$expected = <<<HTML


<div id="testmap" style="width:300; height: 800;"
 class=" mappable"
data-map
data-centre='{"lat":13.8188931,"lng":100.5005558}'
data-zoom=2
data-maptype='road'
data-allowfullscreen='1'
data-clusterergridsize=50,
data-clusterermaxzoom=17,
data-enableautocentrezoom=false
data-mapmarkers='[{"latitude":13.8188931,"longitude":100.5005558,"html":"MEMBER: Test User","category":"default","icon":false}]'
data-defaultHideMarker=false
data-lines='[]'
data-kmlfiles='[]'
data-mapstyles='[]'
data-useclusterer=false
>
</div>

HTML;
		$this->assertEquals($expected, $html);
	}

	/**
	 * The existenve of 'TestKeyVal' in the markers is the test here
	 *
	 * FIXME: Leading blank space
	 */
	public function testSetMarkerTemplateValues() {
		$instance = $this->getInstance();
		$instance->MapPinEdited = true;
		$vals = array('TestKey' => ' TestKeyVal');
		$instance->setMarkerTemplateValues($vals);
		$html = $instance->getRenderableMap(300, 800, 2)->setDivId('testmap')->forTemplate()->getValue();
		$expected = <<<HTML


<div id="testmap" style="width:300; height: 800;"
 class=" mappable"
data-map
data-centre='{"lat":13.8188931,"lng":100.5005558}'
data-zoom=2
data-maptype='road'
data-allowfullscreen='1'
data-clusterergridsize=50,
data-clusterermaxzoom=17,
data-enableautocentrezoom=false
data-mapmarkers='[{"latitude":13.8188931,"longitude":100.5005558,"html":"MEMBER: Test User TestKeyVal","category":"default","icon":false}]'
data-defaultHideMarker=false
data-lines='[]'
data-kmlfiles='[]'
data-mapstyles='[]'
data-useclusterer=false
>
</div>

HTML;

		$this->assertEquals($expected, $html);
	}



	public function testStaticMapChangeLocation() {
		$instance = $this->getInstance();
		$instance->Lat = 13.84;
		$instance->Lon = 100.52;
		$html = $instance->StaticMap(300, 800);
		$expected = '<img src="//maps.googleapis.com/maps/api/staticmap?center='
				  . '13.84%2C100.52&amp;markers=13.84%2C100.52'
				  . '&amp;zoom=13&amp;size=300x800&amp;sensor=false&amp;mapt'
				  . 'ype=roadmap" width="300" height="800" alt="User" />';
		$this->assertEquals($expected, $html);
	}

	public function testStaticMapVarySize() {
		$instance = $this->getInstance();
		$instance->Lat = 13.8188931;
		$instance->Lon = 100.5005558;
		$html = $instance->StaticMap(300, 800);
		$expected = '<img src="//maps.googleapis.com/maps/api/staticmap?center='
				  . '13.8188931%2C100.5005558&amp;markers=13.8188931%2C100.5005'
				  . '558&amp;zoom=13&amp;size=300x800&amp;sensor=false&amp;mapt'
				  . 'ype=roadmap" width="300" height="800" alt="User" />';
		$this->assertEquals($expected, $html);

		$html = $instance->StaticMap(310, 810);
		$expected = '<img src="//maps.googleapis.com/maps/api/staticmap?center='
				  . '13.8188931%2C100.5005558&amp;markers=13.8188931%2C100.5005'
				  . '558&amp;zoom=13&amp;size=310x810&amp;sensor=false&amp;mapt'
				  . 'ype=roadmap" width="310" height="810" alt="User" />';
		$this->assertEquals($expected, $html);
	}


	public function testStaticMapVaryTerrain() {
		$instance = $this->getInstance();
		$instance->Lat = 13.8188931;
		$instance->Lon = 100.5005558;
		$html = $instance->StaticMap(300, 800, null, 'satellite');
		$expected = '<img src="//maps.googleapis.com/maps/api/staticmap?center='
				  . '13.8188931%2C100.5005558&amp;markers=13.8188931%2C100.5005'
				  . '558&amp;zoom=13&amp;size=300x800&amp;sensor=false&amp;mapt'
				  . 'ype=satellite" width="300" height="800" alt="User" />';
		$this->assertEquals($expected, $html);

		$html = $instance->StaticMap(300, 800, null, 'terrain');
		$expected = '<img src="//maps.googleapis.com/maps/api/staticmap?center='
				  . '13.8188931%2C100.5005558&amp;markers=13.8188931%2C100.5005'
				  . '558&amp;zoom=13&amp;size=300x800&amp;sensor=false&amp;mapt'
				  . 'ype=terrain" width="300" height="800" alt="User" />';

		$html = $instance->StaticMap(300, 800, null, 'hybrid');
		$expected = '<img src="//maps.googleapis.com/maps/api/staticmap?center='
				  . '13.8188931%2C100.5005558&amp;markers=13.8188931%2C100.5005'
				  . '558&amp;zoom=13&amp;size=300x800&amp;sensor=false&amp;mapt'
				  . 'ype=hybrid" width="300" height="800" alt="User" />';
		$this->assertEquals($expected, $html);
	}

	public function testStaticMapVaryZoom() {
		$instance = $this->getInstance();

		$html = $instance->StaticMap(300, 800, 2);
		$expected = '<img src="//maps.googleapis.com/maps/api/staticmap?center='
				  . '13.8188931%2C100.5005558&amp;markers=13.8188931%2C100.5005'
				  . '558&amp;zoom=2&amp;size=300x800&amp;sensor=false&amp;mapt'
				  . 'ype=roadmap" width="300" height="800" alt="User" />';
		$this->assertEquals($expected, $html);

		$html = $instance->StaticMap(300, 800, 12);
		$expected = '<img src="//maps.googleapis.com/maps/api/staticmap?center='
				  . '13.8188931%2C100.5005558&amp;markers=13.8188931%2C100.5005'
				  . '558&amp;zoom=12&amp;size=300x800&amp;sensor=false&amp;mapt'
				  . 'ype=roadmap" width="300" height="800" alt="User" />';
		$this->assertEquals($expected, $html);
	}

	public function testStaticMapWithMapPin() {
		$instance = $this->getInstance();
		$this->addMapPinToInstance($instance);

		$html = $instance->StaticMap(300, 800, 2);
		$expected = '<img src="//maps.googleapis.com/maps/api/staticmap?center='
				  . '13.8188931%2C100.5005558&amp;markers=icon%3Ahttp%3A%2F%2Fd'
				  . 'ev.jakayanrides.com%2Fmappable%2Ftests%2Fimages%2Fmapicont'
				  . 'est.png%7C13.8188931%2C100.5005558&amp;zoom=2&amp;size=300'
				  . 'x800&amp;sensor=false&amp;maptype=roadmap" width="300" hei'
				  . 'ght="800" alt="User, Test" />';
		$this->assertEquals($expected, $html);

	}

	private function getInstance() {
		$instance = new Member();
		$instance->Lat = 13.8188931;
		$instance->Lon = 100.5005558;
		$instance->FirstName = 'Test';
		$instance->Surname = 'User';
		return $instance;
	}


	/**
	 * @param Member $instance
	 */
	private function addMapPinToInstance(&$instance) {
		// Create a pin
		$imageFile = new Image(array('Name' => 'mapicontest.png', 'Filename' => 'mappable/tests/images/mapicontest.png'));
		$imageFile->write();
		$instance->MapPinIconID = $imageFile->ID;
		$instance->write();
	}

}
