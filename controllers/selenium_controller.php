<?php
App::import('Lib', 'Selenium.SeleniumTestManager');

class SeleniumController extends AppController {
	public $helpers = array('Html', 'Selenium.Selenium');
	public $uses = array();

	function index() {
		$this->layout = 'testsuite';
		$testcases =& SeleniumTestManager::getTestCaseList();
		$this->set(compact('testcases'));
	}

	function testcase() {
		$this->layout = 'testcase';

		$case = $this->params['url']['case'];
		$plugin = null;
		if (!empty($this->params['url']['plugin'])) {
			$plugin = $this->params['url']['plugin'];
		}

		$this->set(compact('case', 'plugin'));
	}
}