<?php
App::import('Vendor', 'Selenium.Testing_Selenium', array('file' => 'remote-control' . DS . 'client' . DS . 'Testing' . DS . 'Selenium.php'));

class SeleniumTestCase extends CakeTestCase {
	/**
	 * Hold Testing_Selenium object
	 */
	var $selenium;

	function start() {
		$java = exec('which java');
		$server = App::pluginPath('selenium') . 'vendors' . DS . 'remote-control' . DS . 'server' . DS . 'selenium-server.jar';
		$command = $java . ' -jar ' . $server . ' > /dev/null 2>&1 &';
		exec($command);
		return parent::start();
	}

	function before($method) {
		if (!in_array(strtolower($method), $this->methods)) {
			$this->selenium = new Testing_Selenium('*firefox', 'http://localhost:8888/');
			$this->selenium->start();
		}

		return parent::before($method);
	}

	function after($method) {
		if (!in_array(strtolower($method), $this->methods)) {
			$this->selenium->stop();
		}
		return parent::after($method);
	}

	function __call($method, $params) {
		return call_user_func_array(array($this->selenium, $method), $params);
	}
}