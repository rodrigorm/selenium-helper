<?php $path = TESTS . 'selenium' . DS . 'core' . DS . $case;
if (!empty($plugin)):
	$plugin = Inflector::underscore($plugin);
	$pluginPaths = App::path('plugins');
	foreach ($pluginPaths as $pluginPath):
		$path = $pluginPath . $plugin . DS . 'tests' . DS . 'selenium' . DS . 'core' . DS . $case;
		if (file_exists($path)):
			break;
		endif;
	endforeach;
endif;
include($path) ?>