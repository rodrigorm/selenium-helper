<?php
class SeleniumServer {
	static $server;
	static $pid = false;
	
	static function start() {
		if (!SeleniumServer::install()) {
			return false;
		}
		if (SeleniumServer::running()) {
			return true;
		}
		$java = exec('which java');
		$command = $java . ' -jar ' . SeleniumServer::$server . ' > /dev/null 2>&1 & echo $!';
		exec($command, $op);
		SeleniumServer::$pid = (int)$op[0];
		return SeleniumServer::running();
	}

	function stop() {
		posix_kill(SeleniumServer::$pid, 9);
	}

	function running() {
		return SeleniumServer::$pid && posix_kill(SeleniumServer::$pid, 0);
	}

	function install() {
		$paths = App::path('vendors');
		foreach ($paths as $path) {
			if (file_exists($path . 'selenium-server' . DS . 'selenium-server.jar')) {
				SeleniumServer::$server = $path . 'selenium-server' . DS . 'selenium-server.jar';
				return true;
			}
		}
		return false;
	}

	function client($browser, $url) {
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
}