<?php
App::import('Lib', 'Selenium.SeleniumTestManager');

class SeleniumController extends AppController {
	public $helpers = array('Html', 'Selenium.Selenium');
	public $uses = array();

	function beforeFilter() {
		if (isset($this->Auth)) {
			$this->Auth->allow('index', 'testcase', 'cookie');
		}
		parent::beforeFilter();
	}

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

	function cookie($value = 'selenium') {
		setcookie('selenium', $value, strtotime('+60 seconds'), '/');
		exit('Cookie created');
	}
}