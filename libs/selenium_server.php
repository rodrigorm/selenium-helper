<?php
class SeleniumServer {
	static $server = false;
	static $pid = false;

	static function start() {
		SeleniumServer::_getPid();
		if (!SeleniumServer::install()) {
			return false;
		}
		if (SeleniumServer::running()) {
			return true;
		}
		$java = exec('which java');
		$command = $java . ' -jar ' . SeleniumServer::$server . ' > /dev/null 2>&1 & echo $!';
		exec($command, $op);
		SeleniumServer::_setPid((int)$op[0]);
		return SeleniumServer::running();
	}

	static function stop() {
		posix_kill(SeleniumServer::_getPid(), 9);
	}

	static function running() {
		return SeleniumServer::$pid && posix_kill(SeleniumServer::$pid, 0);
	}

	static function install() {
		if (SeleniumServer::$server) {
			return true;
		}
		$paths = App::path('vendors');
		foreach ($paths as $path) {
			if (file_exists($path . 'selenium-server' . DS . 'selenium-server.jar')) {
				SeleniumServer::$server = $path . 'selenium-server' . DS . 'selenium-server.jar';
				return true;
			}
		}
		return false;
	}

	static function client($browser, $url) {
		if (!SeleniumServer::running()) {
			return false;
		}

		$client = new Testing_Selenium($browser, $url);
		do {
			$started = false;
			try {
				$started = @$client->start();
			} catch (Exception $e) {}
		} while(SeleniumServer::running() && !$started);
		return $client;
	}

	static function _setPid($pid) {
		SeleniumServer::$pid = $pid;
		$pidFile = SeleniumServer::_pidFile();
		file_put_contents($pidFile, $pid);
	}

	static function _getPid() {
		if (SeleniumServer::running()) {
			return SeleniumServer::$pid;
		}

		$pidFile = SeleniumServer::_pidFile();
		if (!file_exists($pidFile)) {
			return false;
		}
		SeleniumServer::$pid = (int)file_get_contents($pidFile);
		return SeleniumServer::$pid;
	}

	static function _pidFile() {
		SeleniumServer::install();
		return '/tmp/selenium_server_' . md5(SeleniumServer::$server) . '.pid';
	}
}