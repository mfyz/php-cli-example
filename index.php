#!/usr/bin/php
<?php

require __DIR__ . '/vendor/autoload.php';

use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;
use splitbrain\phpcli\Colors;
use splitbrain\phpcli\TableFormatter;

class MyScript extends CLI {
	
	protected function setup(Options $options) {
		$options->setHelp('A simple example that does nothing but print a version');
		$options->registerOption('version', 'print version', 'v');
		$options->registerCommand('foo', 'Say bar');
		$options->registerCommand('table', 'An example table view');
	}

	protected function main(Options $options) {
		if ($options->getOpt('version')) {
			$this->info('1.0.0');
		}
		else {
			switch ($options->getCmd()) {
				case 'foo': 
					$this->foo($options); break;
				case 'table': 
					$this->table($options); break;
				default:
					print $options->help();
			}
		}
	}
	
	protected function foo(Options $options) {
		print 'bar!';
	}

	protected function table(Options $options)
	{
		$tf = new TableFormatter($this->colors);
		$tf->setBorder(' | ');
		echo $tf->format(
			array('*', '30%', '30%'),
			array('ini setting', 'global', 'local')
		);
		echo str_pad('', $tf->getMaxWidth(), '-') . "\n";
		$ini = ini_get_all();
		foreach ($ini as $val => $opts) {
			echo $tf->format(
				array('*', '30%', '30%'),
				array($val, $opts['global_value'], $opts['local_value']),
				array(Colors::C_CYAN, Colors::C_RED, Colors::C_GREEN)
			);
		}
	}

}

$cli = new MyScript();
$cli->run();
