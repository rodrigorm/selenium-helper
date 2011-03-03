<?php
require_once CAKE . 'console' . DS . 'libs' . DS . 'testsuite.php';

/**
 * Selenium Shell
 *
 * This Shell allows the running of Selenium test suites via the cake command line
 *
 */
class SeleniumShell extends TestSuiteShell {
/**
 * Initialization method
 *
 * @return void
 * @access public
 */
	function initialize() {
		parent::initialize();
	}

/**
 * Gets a manager instance, and set the app/plugin properties.
 *
 * @return void
 */
	function getManager() {
		App::import('Lib', 'Selenium.SeleniumTestManager');
		$this->Manager = new SeleniumTestManager();
		$this->Manager->appTest = ($this->category === 'app');
		if ($this->isPluginTest) {
			$this->Manager->pluginTest = $this->category;
		}
	}

/**
 * Help screen
 *
 * @return void
 * @access public
 */
	function help() {
		$this->out('Usage: ');
		$this->out("\tcake selenium category test_type file");
		$this->out("\t\t- category - \"app\" or name of a plugin");
		$this->out("\t\t- test_type - \"case\", \"group\" or \"all\"");
		$this->out("\t\t- test_file - file name with folder prefix and without the (test|group).php suffix");
		$this->out();
		$this->out('Examples: ');
		$this->out("\t\tcake selenium app all");
		$this->out();
		$this->out("\t\tcake selenium app case behaviors/debuggable");
		$this->out("\t\tcake selenium app case models/my_model");
		$this->out("\t\tcake selenium app case controllers/my_controller");
		$this->out();
		$this->out("\t\tcake selenium app group mygroup");
		$this->out();
		$this->out("\t\tcake selenium bugs case models/bug");
		$this->out("\t\t  // for the plugin 'bugs' and its test case 'models/bug'");
		$this->out("\t\tcake selenium bugs group bug");
		$this->out("\t\t  // for the plugin bugs and its test group 'bug'");
	}

/**
 * Finds the correct folder to look for tests for based on the input category and type.
 *
 * @param string $category The category of the test.  Either 'app' or a plugin name.
 * @return string the folder path
 * @access private
 */
	function __findFolderByCategory($category) {
		$folder = '';
		$paths = array(
			'app' => APP
		);
		$typeDir = $this->type === 'group' ? 'groups' : 'cases';

		if (array_key_exists($category, $paths)) {
			$folder = $paths[$category] . 'tests' . DS . 'selenium' . DS . $typeDir . DS;
		} else {
			$pluginPath = App::pluginPath($category);
			if (is_dir($pluginPath . 'tests')) {
				$folder = $pluginPath . 'tests' . DS . 'selenium' . DS . $typeDir . DS;
			}
		}
		return $folder;
	}
}