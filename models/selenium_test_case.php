<?php 
class SeleniumTestCase extends SeleniumAppModel {
	var $useTable = false;

	var $_testExtension = '.test.php';

	function &getTestCaseList() {
		$return = $this->_getTestCaseList();
		return $return;
	}

	function &_getTestCaseList() {
		$return = array(
			'app' => $this->_getTestFileList(TESTS . 'selenium' . DS . 'core' . DS),
			'plugins' => array()
		);
		$plugins = App::objects('plugin');
		$pluginPaths = App::path('plugins');
		foreach ($plugins as $plugin) {
			$pluginFileList = array();
			foreach ($pluginPaths as $pluginPath) {
				$pluginFileList = array_merge($pluginFileList, $this->_getTestFileList($pluginPath . $plugin . DS . 'tests' . DS . 'selenium' . DS . 'core' . DS));
			}
			$return['plugins'][$plugin] = $pluginFileList;
		}
		return $return;
	}

	function &_getTestFileList($directory = '.') {
		$return = $this->_getRecursiveFileList($directory, array(&$this, '_isTestCaseFile'));
		foreach ($return as $key => $testCaseFile) {
			$return[$key] = str_replace($directory . DS, '', $testCaseFile);
		}
		return $return;
	}

	function &_getRecursiveFileList($directory = '.', $fileTestFunction) {
		$fileList = array();
		if (!is_dir($directory)) {
			return $fileList;
		}

		$files = glob($directory . DS . '*');
		$files = $files ? $files : array();

		foreach ($files as $file) {
			if (is_dir($file)) {
				$fileList = array_merge($fileList, $this->_getRecursiveFileList($file, $fileTestFunction));
			} elseif ($fileTestFunction[0]->$fileTestFunction[1]($file)) {
				$fileList[] = $file;
			}
		}
		return $fileList;
	}

	function _isTestCaseFile($file) {
		return $this->_hasExpectedExtension($file, $this->_testExtension);
	}

	function _hasExpectedExtension($file, $extension) {
		return $extension == strtolower(substr($file, (0 - strlen($extension))));
	}
}