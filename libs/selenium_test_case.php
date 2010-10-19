<?php
App::import('Lib', 'Selenium.SeleniumServer');
App::import('Vendor', 'Selenium.Testing_Selenium', array('file' => 'remote-control' . DS . 'Testing' . DS . 'Selenium.php'));

class SeleniumTestCase extends CakeTestCase {
/**
 * Hold Testing_Selenium object
 */
	var $selenium;

	function start() {
		$this->__installSeleniumRC();
		SeleniumServer::start();
		return parent::start();
	}

	function before($method) {
		if (!in_array(strtolower($method), $this->methods)) {
			$this->selenium = SeleniumServer::client($this->_getBrowser(), $this->_getUrl());
			$speed = $this->_getSpeed();
			if ($speed) {
				$this->selenium->setSpeed($speed);
			}
			$this->selenium->createCookie('selenium=yes', 'path=/, max_age=10000');
		}

		return parent::before($method);
	}

	function after($method) {
		if (!in_array(strtolower($method), $this->methods)) {
			$this->selenium->deleteCookie('selenium', 'path=/');
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

	function _getSpeed() {
		return $this->_getArg('speed', false);
	}

	function _getArg($name, $default = '') {
		global $argv;
		if (empty($argv)) {
			if (!empty($_GET[$name])) {
				return $_GET[$name];
			}
			return $default;
		}
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
		if (!SeleniumServer::install()) {
			$this->err(__('Sorry, Selenium RC could not be found. Download it from http://seleniumhq.org/ and install it to your vendors directory.', true));
			exit;
		}
	}

	function getLocalLocation() {
		return str_replace($this->_getUrl(), '/', $this->getLocation());
	}
}