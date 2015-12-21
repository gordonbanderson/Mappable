<?php

class MapExtensionTest extends SapphireTest {
	protected static $fixture_file = 'mappable/tests/mapextensions.yml';

	protected $usesDatabase = false;

	public function setUp() {
		$cache = SS_Cache::factory('elasticsearch');
		$cache->clean(Zend_Cache::CLEANING_MODE_ALL);
		SS_Cache::set_cache_lifetime('elasticsearch', 3600, 1000);

		// this needs to be called in order to create the list of searchable
		// classes and fields that are available.  Simulates part of a build
		$classes = array();
		$this->requireDefaultRecordsFrom = $classes;

		// add MapExtension extension where appropriate
		Member::add_extension('MapExtension');
		parent::setUp();
	}


	public function setUpOnce() {
		parent::setupOnce();
	}


	public function testupdateCMSFields() {

	}


	public function testGetMappableLatitude() {
		$instance = $this->getInstance();
		$instance->Lat = 42.1;
		$instance->write();
		$this->assertEquals(
			42.1,
			$instance->getMappableLatitude()
		);
	}


	public function testGetMappableLongitude() {
		$instance = $this->getInstance();
		$instance->Lon = 42.1;
		$this->assertEquals(
			42.1,
			$instance->getMappableLongitude()
		);
	}


	public function testgetMappableMapContent() {

	}


	public function testonBeforeWriteMapPinNotEdited() {
		$instance = $this->getInstance();
		$this->Lat = 0;
		$this->Lon = 0;
		$instance->write();
		$this->assertFalse($instance->MapPinEdited);
	}

	public function testonBeforeWriteMapPinEdited() {
		$instance = $this->getInstance();

		// north
		$this->showMapPinEdited($instance, 10, 0);

		// north west
		$this->showMapPinEdited($instance, 10, -10);

		// west
		$this->showMapPinEdited($instance, 0, -10);

		// south west
		$this->showMapPinEdited($instance, -10, -10);

		// south
		$this->showMapPinEdited($instance, -10, 0);

		// south east
		$this->showMapPinEdited($instance, -10, 10);

		// east
		$this->showMapPinEdited($instance, 0, 10);

		// north east
		$this->showMapPinEdited($instance, 10, 10);

	}


	public function testgetMappableMapPin() {

	}


	public function testHasGeoWest() {
		$instance = $this->getInstance();
		$instance->Lon = -20;
		$this->assertTrue($instance->HasGeo());
	}

	public function testHasGeoEast() {
		$instance = $this->getInstance();
		$instance->Lon = 20;
		$this->assertTrue($instance->HasGeo());
	}

	public function testHasGeoNorth() {
		$instance = $this->getInstance();
		$instance->Lat = 20;
		$this->assertTrue($instance->HasGeo());
	}

	public function testHasGeoNorthWest() {
		$instance = $this->getInstance();
		$instance->Lat = 20;
		$instance->Lon = -20;
		$this->assertTrue($instance->HasGeo());
	}

	public function testHasGeoNortEast() {
		$instance = $this->getInstance();
		$instance->Lat = 20;
		$instance->Lon = 20;
		$this->assertTrue($instance->HasGeo());
	}

	public function testHasGeoSouth() {
		$instance = $this->getInstance();
		$instance->Lat = -20;
		$this->assertTrue($instance->HasGeo());
	}

	public function testHasGeoSouthWest() {
		$instance = $this->getInstance();
		$instance->Lat = -20;
		$instance->Lon = -20;
		$this->assertTrue($instance->HasGeo());
	}

	public function testHasGeoSouthEast() {
		$instance = $this->getInstance();
		$instance->Lat = -20;
		$instance->Lon = 20;
		$this->assertTrue($instance->HasGeo());
	}

	public function testHasGeoOrigin() {
		$instance = $this->getInstance();
		$instance->Lat = 0;
		$instance->Lon = 0;
		$this->assertFalse($instance->HasGeo());
	}

	public function testHasGeoOriginMapLayerExtension() {
		$instance = $this->getInstance();
		$instance->Lat = 0;
		$instance->Lon = 0;
		$this->assertFalse($instance->HasGeo());
		Page::add_extension('MapLayerExtension');
		Page::remove_extension('MapLayerExtension');
		$this->markTestSkipped('TODO');
	}

	public function testHasGeoOriginPointsOfInterestLayers() {
		$instance = $this->getInstance();
		$instance->Lat = 0;
		$instance->Lon = 0;
		$this->assertFalse($instance->HasGeo());
		$this->markTestSkipped('TODO');
	}

	public function testBasicMap() {

	}


	public function testgetMapField() {
		$instance = $this->getInstance();
		$this->Lat = 37.1;
		$this->Lon = 28;
		$this->Zoom = 12;
		$mapField  = $instance->getMapField();
		$this->assertInstanceOf('LatLongField', $mapField);
	}


	public function testUseCompressedAssets() {

	}


	private function getInstance() {
		$instance = new Member();
		return $instance;
	}

	private function showMapPinEdited(&$instance, $lat, $lon) {
		$instance->Lat = $lat;
		$instance->Long = $lon;
		$instance->write();
		$this->assertTrue($instance->MapPinEdited);
	}



}
