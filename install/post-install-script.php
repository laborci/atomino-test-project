<?php

$projectRoot = realpath(__DIR__.'/..');

function struct($tree, $root) {
	$root = rtrim($root, '/') . '/';
	foreach ($tree as $name => $value) {
		if (is_array($value)) {
			mkdir($root . $name, 0777, true);
			struct($value, $root . $name);
		} else {
			touch($root . $value);
		}
	}
}

// create needed folders

struct([
	'.my-repos'=>[],
	'app' => [
		'data'   => [
			'attachments' => ['.gitkeep'],
		],
		'etc'    => ['.gitkeep'],
		'public' => ['.gitkeep'],
		'tmp'    => ['.gitkeep'],
		'log' => ['.gitkeep'],
	],
], $projectRoot);

// copy / create base files

rename($projectRoot . '/install/atomino.ini', $projectRoot . '/atomino.ini');
rename($projectRoot . '/install/vhost', $projectRoot . '/var/vhost');
unlink($projectRoot . '/.gitignore');
rename($projectRoot . '/install/.gitignore.dist', $projectRoot . '/.gitignore');
file_put_contents($projectRoot . '/var/etc/version', '1');

// modify the composer.json

$composer = json_decode(file_get_contents($projectRoot . '/composer.json'), true);
$composer['authors'] = [];
unset($composer['description']);
unset($composer['repositories']);
$composer['name'] = basename($projectRoot).'/project';
file_put_contents($projectRoot . '/composer.json', json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

// remove installer

unlink($projectRoot . '/install/post-install-script.php');
