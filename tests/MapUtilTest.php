<?php

class MapUtilTest extends SapphireTest {

	/*
	Other tests:
	1) List, ArrayList, DataList, null for get_map
	2) Negative and zero map sizes
	3) Invalid map type


	public function setUpOnce() {
		$this->requiredExtensions = array(
			'Member' => array('MapExtension')
		);
		parent::setupOnce();
	}
*/
	public function setUp() {
		MapUtil::reset();
		parent::setUp();
	}

	public function test_set_api_key_string() {
		MapUtil::set_api_key('PRETENDAPIKEY');
		$html = $this->htmlForMap();
		$this->fail('Where is this used?');
	}

	public function test_set_api_key_host_array() {
		$url = Director::absoluteBaseURL();
		// remove http and https
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);
		$parts = explode('/', $url);
		$host = $parts[0];
		$key = array($host => 'PRETENDAPIKEY');
		MapUtil::set_api_key($key);
		$html = $this->htmlForMap();
		$this->fail('Where is this used?');
	}

	public function test_set_map_size() {
		MapUtil::set_map_size('890px', '24em');
		$html = $this->htmlForMap();
		$this->assertContains(' style="width:890px; height: 24em;"', $html);
	}

	public function testSanitizeEmptyString() {
		$this->assertEquals(
			'',
			MapUtil::sanitize('')
		);
	}

	public function testSanitizeAlreadySanitized() {
		$this->assertEquals(
			'This is already sanitized',
			MapUtil::sanitize('This is already sanitized')
		);
	}

	public function testSanitizeSlashN() {
		$this->assertEquals(
			'String to be sanitized',
			MapUtil::sanitize("String\n to be sanitized")
		);
	}

	public function testSanitizeSlashT() {
		$this->assertEquals(
			'String to be sanitized',
			MapUtil::sanitize("String\t to be sanitized")
		);
	}

	public function testSanitizeSlashR() {
		$this->assertEquals(
			'String to be sanitized',
			MapUtil::sanitize("String\r to be sanitized")
		);
	}

	/**
	 * A single marker for the Member should appear in the UJS map data
	 */
	public function testSingularMappableItemMarkerUJSExists() {
		Member::add_extension('MapExtension');
		$member = new Member();
		$member->Lat = 12.847;
		$member->Lon = 29.24;

		// because we are not writing, set this manually
		$member->MapPinEdited = true;
		$list = new ArrayList();
		$list->push($member);
		$map = MapUtil::get_map($list, array());
		$html = $map->forTemplate();
		$markerExpected = 'data-mapmarkers=\'[{"latitude":12.847,"longitude":29.24,"html":"MEMBER: ","category":"default","icon":false}]\'';
		$this->assertContains($markerExpected, $html);
		Member::remove_extension('MapExtension');
	}

	private function htmlForMap() {
		$map = MapUtil::get_map(new ArrayList(), array());
		$html = $map->forTemplate();
		return $html;
	}


	// These appear to test code that's not used
	public function test_set_center() {
		MapUtil::set_center('Klong Tan, Bangkok, Thailand');
		$html = $this->htmlForMap();
		//coordinates of Klong Tan in Bangkok
		$expected = 'data-centre=\'{"lat":13.7243075,"lng":100.5718086}';
		$this->assertContains($expected, $html);
	}

	 public function test_set_map_type() {
		MapUtil::set_map_type('google.maps.MapTypeId.G_HYBRID_MAP');
		$html = $this->htmlForMap();
		$this->fail('No effect for set map type');

	}

	 public function test_set_info_window_width() {
	 	MapUtil::set_info_window_width(420);
	 	$html = $this->htmlForMap();
	 	$this->fail('No evidence of set info width being used');
	}

	 public function test_set_icon_size() {
	 	MapUtil::set_icon_size(14, 37);
	 	$html = $this->htmlForMap();
	 	$html = $this->htmlForMap();
	 	$this->fail('No effect for set icon size');
	}

}
