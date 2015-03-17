<?php

##{copyright}##
define("IN_SOTER", true);
//引入核心
require dirname(__FILE__) . '/soter.php';
//项目目录路径
define('SOTER_APP_PATH', Sr::realPath(dirname(__FILE__) . '/../../tests/application') . '/');
//项目拓展包路径
define('SOTER_PACKAGES_PATH', SOTER_APP_PATH . 'packages/');
//初始化系统配置
Soter::initialize()
	//项目目录路径
	->setApplicationDir(SOTER_APP_PATH)
	//注册项目包
	->addPackage(SOTER_APP_PATH)
	//注册拓展包
	->addPackages(array(
		//SOTER_PACKAGES_PATH . 'misc',
	))
	//注册自动加载的函数文件
	->addAutoloadFunctions(array(
		// 'functions'
	))
	//设置运行环境
	->setEnvironment(($env = (($cliEnv = Sr::getOpt('env')) ? $cliEnv : Sr::arrayGet($_SERVER, 'ENVIRONMENT'))) ? Sr::config()->getServerEnvironment($env) : Sr::ENV_DEVELOPMENT)
	//系统错误显示设置，非产品环境才显示
	->setShowError(Sr::config()->getEnvironment() != Sr::ENV_PRODUCTION)
	/**
	 * 下面配置中可以使用：
	 * 1.主项目的claseses目录，主项目类库目录，主项目拓展包里面的类
	 * 2.这几个目录如果存在同名类，使用的优先级高到低是：
	 *   主项目classes->类库classes->拓展包classes->拓展包类库classes
	 */
	//入口文件所在目录
	->setIndexDir(dirname(__FILE__) . '/')
	//入口文件名称
	->setIndexName(pathinfo(__FILE__, PATHINFO_BASENAME))
	//宕机维护模式
	->setIsMaintainMode(true)
	//宕机维护模式IP白名单
	->setMaintainIpWhitelist(array('127.0.0.2','192.168.0.2/32'))
	//宕机维护模式处理方法
	->setMaintainModeHandle(new Soter_Maintain_Default_Handle())
	//初始化请求
	->setRequest(new Soter_Request(Sr::arrayGet($_SERVER, 'REQUEST_URI')))
	//注册默认pathinfo路由器
	->addRouter(new Soter_Default_Router_PathInfo())
	//pathinfo路由器,注册uri重写
	->setUriRewriter(new Soter_Uri_Rewriter_Default())
	//注册默认get路由器
	->addRouter(new Soter_Default_Router_Get())
	//get路由器,url中的控制器的get变量名
	->setRouterUrlControllerKey('c')
	//get路由器,url中的方法的get变量名
	->setRouterUrlMethodKey('a')
	//get路由器,url中的hmvc模块的get变量名
	->setRouterUrlModuleKey('m')
	//设置自定义的错误显示控制处理类
	->setExceptionHandle(new Soter_Exception_Handle_Default())
	//错误日志记录，注释掉这行会关闭日志记录，去掉注释则开启日志文件记录
	->addLoggerWriter(new Soter_Logger_FileWriter(SOTER_APP_PATH . 'storage/logs/'))
	//设置日志记录子目录格式，参数就是date()函数的第一个参数,默认是 Y-m-d/H
	->setLogsSubDirNameFormat('Y-m-d/H')
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
		// 'Demo' => 'demo'
	))
	//加载项目自定义bootstrap.php配置,这一句一定要在最后，确保能覆盖上面的配置
	->bootstrap()
;

//启动，噪起来
Soter::run();


