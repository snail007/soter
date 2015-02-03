<?php

defined('IN_SOTER') or die();

Soter::getConfig()
	->addPackages(array(
	    dirname(__FILE__) . '/packages/misc/'
	))
	->addLoggerWriter(new Logger_MyWriter())
	->setExceptionHandle(new Exception_Handle())
	->setDefaultController('Welcome')
	->setDefaultMethod('index')
	->setMethodPrefix('do_')
	->setMethodUriSubfix('.html')
	
;