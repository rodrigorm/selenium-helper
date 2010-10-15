<?php
App::import('Lib', 'Selenium.SeleniumTestCase');

class SeleniumControllerTestCase extends SeleniumTestCase {
	function testIndex() {
		$this->open('/selenium/selenium/index');
		$this->assertTrue($this->isTextPresent('CakePHP Test Suite 1.3'));
		$this->assertTrue($this->isTextPresent('App'));
		$this->assertTrue($this->isTextPresent('Plugins'));
	}

	function testTestcase() {
		$this->open('/selenium/selenium/testcase?case=index.test.php&plugin=selenium');
		$this->assertTrue($this->isTextPresent('open'));
		$this->assertTrue($this->isTextPresent('/selenium'));
	}
}