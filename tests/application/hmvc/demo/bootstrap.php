<?php

defined('IN_SOTER') or die();

Soter::getConfig()
	//注册拓展包到主包容器，以保证优先级大于主项目
	->addMasterPackages(array(
	    dirname(__FILE__) . '/packages/misc/'
	))
	//注册自动加载的函数文件
	->addAutoloadFunctions(array(
	    'functions'
	))
	//设置运行环境
	//->setEnvironment(($env = (($cliEnv = getopt('', array('env:'))) ? $cliEnv['env'] : Sr::arrayGet($_SERVER, 'ENVIRONMENT'))) ? Sr::config()->getServerEnvironment($env) : Sr::ENV_DEVELOPMENT)
	//系统错误显示设置，非产品环境才显示
	//->setShowError(Sr::config()->getEnvironment() != Sr::ENV_PRODUCTION)
	/**
	 * 下面配置中可以使用：
	 * 1.主项目的claseses目录，类库目录，拓展包里面的类
	 * 2.当前hmvc子项目的claseses目录，类库目录，拓展包里面的类
	 * 3.这几个目录如果存在同名类，使用的优先级高到低是：
	 * hmvc子项目拓展包->hmvc子项目类库目录->hmvc子项目claseses目录->主项目拓展包->主项目类库目录->主项目claseses目录
	 */
	//->addLoggerWriter(new Logger_MyWriter())
	//->setExceptionHandle(new Exception_Handle())
	->setDefaultController('Welcome')
	->setDefaultMethod('index')
	->setMethodPrefix('do_')
	->setMethodUriSubfix('.html')

;
