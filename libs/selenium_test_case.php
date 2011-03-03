<?php
App::import('Vendor', 'Selenium.Testing_Selenium', array('file' => 'remote-control' . DS . 'Testing' . DS . 'Selenium.php'));

class SeleniumTestCase extends CakeTestCase {
	var $selenium;

	var $settings;

	var $cookie;

	function __construct() {
		$this->cookie = 'stc' . rand(0, 100);
	}

	function start() {
		$defaults = array(
			'browser' => '*firefox',
			'url'     => 'http://localhost:8888/',
			'host'    => 'localhost',
			'port'    => 4444,
			'speed'   => false
		);
		Configure::load('selenium');
		$settings = Configure::read('Selenium');
		if (!is_array($settings)) {
			$settings = array();
		}
		$shell = array(
			'browser' => $this->_getBrowser(),
			'url'     => $this->_getUrl(),
			'host'    => $this->_getHost(),
			'port'    => 4444,
			'speed'   => $this->_getSpeed()
		);
		$shell = array_filter($shell);
		$this->settings = array_merge($defaults, $settings, $shell);
		return parent::start();
	}

	function before($method) {
		if (!in_array(strtolower($method), $this->methods)) {
			extract($this->settings);

			$this->selenium = new Testing_Selenium($browser, $url, $host, $port);
			$this->selenium->start();
			
			if ($this->settings['speed']) {
				$this->selenium->setSpeed($this->settings['speed']);
			}
			$this->selenium->open('/selenium/selenium/cookie/' . $this->cookie);
			$this->assertTrue($this->selenium->isCookiePresent('selenium'));
			$this->assertTrue($this->selenium->isTextPresent('Cookie created'));
			$this->assertEqual($this->selenium->getCookieByName('selenium'), $this->cookie);
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
		return $this->_getArg('browser', false);
	}

	function _getUrl() {
		return $this->_getArg('url', false);
	}

	function _getSpeed() {
		return $this->_getArg('speed', false);
	}

	function _getHost() {
		return $this->_getArg('host', false);
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

/**
 * Initialize DB connection.
 *
 * @return void
 * @access protected
 */
	function _initDb() {
		$testDbAvailable = in_array('test', array_keys(ConnectionManager::enumConnectionObjects()));

		$_prefix = null;

		if ($testDbAvailable) {
			// Try for test DB
			restore_error_handler();
			@$db =& ConnectionManager::getDataSource('test');
			set_error_handler('simpleTestErrorHandler');
			$testDbAvailable = $db->isConnected();
		}

		// Try for default DB
		if (!$testDbAvailable) {
			$db =& ConnectionManager::getDataSource('default');
		}
		$_prefix = $db->config['prefix'];
		$db->config['prefix'] = $this->cookie . '_';

		ConnectionManager::create('test_suite', $db->config);
		$db->config['prefix'] = $_prefix;

		// Get db connection
		$this->db =& ConnectionManager::getDataSource('test_suite');
		$this->db->cacheSources  = false;

		ClassRegistry::config(array('ds' => 'test_suite'));
	}
}