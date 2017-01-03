<?php

defined('IN_SOTER') or die();

Soter::getConfig()
	//注册拓展包
	->addMasterPackages(array(
	    SOTER_PACKAGES_PATH . 'misc',
	    SOTER_PACKAGES_PATH . 'extensions',
	))
	//注册自动加载的函数文件
	->addAutoloadFunctions(array(
	    'functions_auto'
	))
	//入口文件所在目录
	->setIndexDir(dirname(__FILE__) . '/../')
	//入口文件名称
	->setIndexName('indexfortest.php')
	//设置运行环境
	->setEnvironment(($env = (($cliEnv = Sr::getOpt('env')) ? $cliEnv : Sr::arrayGet($_SERVER, 'ENVIRONMENT'))) ? $env : 'development')
	//系统错误显示设置，非产品环境才显示
	->setShowError(Sr::config()->getEnvironment() != 'production')
	->setDatabseConfig('database')
	/**
	 * 下面配置中可以使用：
	 * 1.主项目的claseses目录，主项目类库目录，主项目拓展包里面的类
	 * 2.这几个目录如果存在同名类，使用的优先级高到低是：
	 * 项目claseses目录->项目类库claseses目录->项目拓展包claseses目录
	 */
	//->addLoggerWriter(new Logger_MyWriter())
	//设置自定义的错误显示控制处理类
	//->setExceptionHandle(new Exception_Handle())
	//日志记录，注释掉这行会关闭日志记录，去掉注释则开启日志文件记录
	->addLoggerWriter(new Soter_Logger_FileWriter(Sr::config()->getStorageDirPath() . 'logs/'))
	//->addLoggerWriter(new Soter_LoggerWriter_Database())
	//设置日志子目录格式，参数就是date()函数的第一个参数,默认是 Y-m-d/H
	->setLogsSubDirNameFormat('Y-m-d/H')
	//设置自定义的uri重写类
	//->setUriRewriter(new Uri_Rewriter())
	//默认控制器
	->setDefaultController('Welcome')
	//默认方法
	->setDefaultMethod('index')
	//方法前缀
	->setMethodPrefix('do_')
	//方法url后缀
	->setMethodUriSubfix('.do')
	//注册hmvc模块，数组键是uri里面的hmvc模块名称，值是hmvc模块文件夹名称
	->setHmvcModules(array(
	    'Demo' => 'demo'
	))
	->setDataCheckRules('rules')


;
