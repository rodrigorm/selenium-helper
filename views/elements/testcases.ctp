<?php foreach ($cases as $case):
	$parts = explode(DS, $case);
	$name = str_replace('.test.php', '', array_pop($parts));
	$name = Inflector::camelize($name);
	array_push($parts, $name);
	$title = $indent . implode(' / ', $parts);
	$url = array(
		'plugin' => 'selenium',
		'controller' => 'selenium',
		'action' => 'testcase',
		'?' => array(
			'case' => $case
		)
	);
	if (!empty($pluginName)):
		$url['?']['plugin'] = $pluginName;
	endif;
	echo $selenium->addTestCase($title, Router::url($url));
endforeach ?>