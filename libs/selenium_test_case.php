<?php
App::import('Vendor', 'Selenium.Testing_Selenium', array('file' => 'remote-control' . DS . 'Testing' . DS . 'Selenium.php'));

class SeleniumTestCase extends CakeTestCase {
/**
 * Hold Testing_Selenium object
 */
	var $selenium;

/**
 * Hold complete path to selenium-server.jar
 */
	var $server;

	function start() {
		$this->__installSeleniumRC();
		$java = exec('which java');
		$command = $java . ' -jar ' . $this->server . ' > /dev/null 2>&1 &';
		exec($command);
		return parent::start();
	}

	function before($method) {
		if (!in_array(strtolower($method), $this->methods)) {
			$this->selenium = new Testing_Selenium($this->_getBrowser(), $this->_getUrl());
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

	function _getBrowser() {
		return $this->_getArg('browser', 'firefox');
	}

	function _getUrl() {
		return $this->_getArg('url', 'http://localhost:8888/');
	}

	function _getArg($name, $default = '') {
		global $argv;
		$argName = '-' . $name;
		foreach ($argv as $key => $arg) {
			if ($arg == $argName) {
				return $argv[$key + 1];
			}
		}

		return $default;
	}

	function __call($method, $params) {
		return call_user_func_array(array($this->selenium, $method), $params);
	}

/**
 * tries to install Selenium RC and exits gracefully if it is not there
 *
 * @return void
 * @access private
 */
	function __installSeleniumRC() {
		$paths = App::path('vendors');
		foreach ($paths as $path) {
			if (file_exists($path . 'selenium-server' . DS . 'selenium-server.jar')) {
				$this->server = $path . 'selenium-server' . DS . 'selenium-server.jar';
				return;
			}
		}
		$this->err(__('Sorry, Selenium RC could not be found. Download it from http://seleniumhq.org/ and install it to your vendors directory.', true));
		exit;
	}
}