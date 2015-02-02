<?php

defined('IN_SOTER') or die();

Soter::getConfig()
        //自定义配置
        //->addLoggerWriter(new Logger_MyWriter())
        //->setExceptionHandle(new Exception_Handle())
        //->addLoggerWriter(new Soter_Logger_FileWriter())
	->setDefaultController('Welcome')
	->setDefaultMethod('index')
	->setMethodPrefix('do_')
	->setMethodUriSubfix('.do')
	->setHmvcModules(array('Vip'=>'vip'))
        ;
