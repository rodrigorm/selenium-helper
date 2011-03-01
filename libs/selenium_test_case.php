<?php
App::import('Vendor', 'Selenium.Testing_Selenium', array('file' => 'remote-control' . DS . 'Testing' . DS . 'Selenium.php'));

class SeleniumTestCase extends CakeTestCase {
	var $selenium;

	var $settings;

	function start() {
		$defaults = array(
			'browser' => $this->_getBrowser(),
			'url'     => $this->_getUrl(),
			'host'    => $this->_getHost(),
			'port'    => 4444,
			'speed'   => $this->_getSpeed()
		);
		$settings = Configure::read('Selenium');
		if (!is_array($settings)) {
			$settings = array();
		}
		$this->settings = array_merge($defaults, $settings);
		return parent::start();
	}

	function before($method) {
		if (!in_array(strtolower($method), $this->methods)) {
			extract($this->settings);

			if (empty($port) || strpos($host, ':') !== false) {
				list($host, $port) = explode(':', $host, 2);
			}

			$this->selenium = new Testing_Selenium($browser, $url, $host, $port);
			$this->selenium->start();
			
			if ($this->settings['speed']) {
				$this->selenium->setSpeed($this->settings['speed']);
			}
			$this->selenium->open('/selenium/selenium/cookie');
			$this->assertTrue($this->selenium->isCookiePresent('selenium'));
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
		return $this->_getArg('browser', '*firefox');
	}

	function _getUrl() {
		return $this->_getArg('url', 'http://localhost:8888/');
	}

	function _getSpeed() {
		return $this->_getArg('speed', false);
	}

	function _getHost() {
		return $this->_getArg('host', 'localhost');
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

	function getLocalLocation() {
		return str_replace($this->settings['url'], '/', $this->getLocation());
	}
}