<?php

class MapUtilTest extends SapphireTest {

	/*
	Other tests:
	1) List, ArrayList, DataList, null for get_map
	2) Negative and zero map sizes
	3) Invalid map type
	 */


	public function test_set_api_key() {
		MapUtil::set_api_key('PRETENDAPIKEY');
		$html = $this->htmlForMap();
		echo $html;
	}

	public function test_get_set_map_already_rendered() {
		MapUtil::set_map_already_rendered(false);
		$this->assertFalse(MapUtil::get_map_already_rendered());
		MapUtil::set_map_already_rendered(true);
		$this->assertTrue(MapUtil::get_map_already_rendered());
	}

	public function test_set_map_size() {
		MapUtil::set_map_size('890px','24em');
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

	public static function test_chooseToAddDataobject() {

	}

	private function htmlForMap() {
		$map = MapUtil::get_map(new ArrayList(),array());
		$html = $map->forTemplate();
		return $html;
	}


	// These appear to test code that's not used
	public function test_set_center() {
		MapUtil::set_center('Bangkok, Thailand');
		$html = $this->htmlForMap();
		echo $html;
		$this->fail('No evidence of map type changing');
	}

	 public function test_set_map_type() {
		MapUtil::set_map_type('google.maps.MapTypeId.G_PHYSICAL_MAP');
		$html = $this->htmlForMap();
		echo $html;
		$this->fail('No evidence of map type changing');
	}

	 public function test_set_info_window_width()  {
	 	$this->fail('No evidence of set info width being used');
	}

	 public function test_set_icon_size() {

	}

}
