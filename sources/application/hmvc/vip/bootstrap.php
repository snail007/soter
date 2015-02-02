<?php

defined('IN_SOTER') or die();

Soter::getConfig()
        //自定义配置
	->addLoggerWriter(new Logger_MyWriter())
        ->setExceptionHandle(new Exception_Handle())
	->setDefaultController('Welcome')
	->setDefaultMethod('index')
	->setMethodPrefix('do_')
	->setMethodUriSubfix('.html')
        ;
