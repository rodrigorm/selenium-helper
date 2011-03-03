<?php
require_once CAKE . 'tests' . DS . 'lib' . DS . 'test_manager.php';

class SeleniumTestManager extends TestManager {
/**
 * Returns the given path to the test files depending on a given type of tests (cases, group, ..)
 *
 * @param string $type either 'cases' or 'groups'
 * @return string The path tests are located on
 * @access protected
 */
	function _getTestsPath($type = 'cases') {
		if (!empty($this->appTest)) {
			if ($type == 'cases') {
				$result = TESTS . 'selenium' . DS . 'cases' . DS;
			} else if ($type == 'groups') {
				$result = TESTS . 'selenium' . DS . 'groups' . DS;
			}
		} else if (!empty($this->pluginTest)) {
			$_pluginBasePath = APP . 'plugins' . DS . $this->pluginTest . DS . 'tests';
			$pluginPath = App::pluginPath($this->pluginTest);
			if (file_exists($pluginPath . DS . 'tests')) {
				$_pluginBasePath = $pluginPath . DS . 'tests';
			}
			$result = $_pluginBasePath . DS . 'selenium' . DS . $type;
		} else {
			$result = false;
		}
		return $result;
	}
}