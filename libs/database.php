<?php
class SeleniumDatabase {
	static function configure() {
		if (empty($_COOKIE['selenium'])) {
			return;
		}

		$cookie = $_COOKIE['selenium'];
		App::import('Model', 'ConnectionManager', false);

		ClassRegistry::flush();
		Configure::write('Cache.disable', true);

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
		$db->config['prefix'] = $cookie . '_';

		ConnectionManager::create('test_suite', $db->config);
		$db->config['prefix'] = $_prefix;

		// Get db connection
		$db =& ConnectionManager::getDataSource('test_suite');
		$db->cacheSources  = false;

		ClassRegistry::config(array('ds' => 'test_suite'));	
	}
}

SeleniumDatabase::configure();