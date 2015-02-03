<?php

defined('IN_SOTER') or die();

Soter::getConfig()
	->addPackages(array(
	    dirname(__FILE__) . '/packages/misc/'
	))
	/**
	 * 下面配置中可以使用：
	 * 1.主项目的claseses目录，类库目录，拓展包里面的类
	 * 2.当前hmvc子项目的claseses目录，类库目录，拓展包里面的类
	 * 3.这几个目录如果存在同名类，使用的优先级高到低是：
	 * hmvc子项目拓展包->hmvc子项目类库目录->hmvc子项目claseses目录->主项目拓展包->主项目类库目录->主项目claseses目录
	 */
	->addLoggerWriter(new Logger_MyWriter())
	->setExceptionHandle(new Exception_Handle())
	->setDefaultController('Welcome')
	->setDefaultMethod('index')
	->setMethodPrefix('do_')
	->setMethodUriSubfix('.html')

;
