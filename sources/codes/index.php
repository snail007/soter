<?php

##{copyright}##
define("IN_SOTER", true);
//引入核心
require dirname(__FILE__) . '/soter.php';
//项目目录路径
define('SOTER_APP_PATH', Sr::realPath(dirname(__FILE__) . '/../application') . '/');
//项目拓展包路径
define('SOTER_PACKAGES_PATH', SOTER_APP_PATH . 'packages/');
//初始化系统配置
Soter::initialize()
       //项目目录路径
	->setApplicationDir(SOTER_APP_PATH)
	//注册拓展包
	->addPackages(array(
	    SOTER_PACKAGES_PATH . 'misc',
	))
	//设置运行环境
	->setEnvironment(($env = (($cliEnv = getopt('', array('env:'))) ? $cliEnv['env'] : Sr::arrayGet($_SERVER, 'ENVIRONMENT'))) ? Sr::config()->getServerEnvironment($env) : Sr::ENV_DEVELOPMENT)
	//系统错误显示设置，非产品环境才显示
	->setShowError(Sr::config()->getEnvironment() != Sr::ENV_PRODUCTION)
	/**
	 * 下面配置中可以使用：
	 * 1.主项目的claseses目录，主项目类库目录，主项目拓展包里面的类
	 * 2.这几个目录如果存在同名类，使用的优先级高到低是：拓展包->类库目录->claseses目录
	 */
	//入口文件所在目录
	->setIndexDir(dirname(__FILE__) . '/')
	//入口文件名称
	->setIndexName(pathinfo(__FILE__, PATHINFO_BASENAME))
	//初始化请求
	->setRequest(new Soter_Request(Sr::arrayGet($_SERVER, 'REQUEST_URI')))
	//默认路由器
	->addRouter(new Soter_Default_Router_PathInfo())
	//->addLoggerWriter(new Logger_MyWriter())
	//设置自定义的错误显示控制处理类
	//->setExceptionHandle(new Exception_Handle())
	//->addLoggerWriter(new Soter_Logger_FileWriter())
	//默认控制器
	->setDefaultController('Welcome')
	//默认方法
	->setDefaultMethod('index')
	//方法前缀
	->setMethodPrefix('do_')
	//方法url后缀
	->setMethodUriSubfix('.do')
	//注册hvmc模块，数组键是uri里面的hmvc模块名称，值是hmvc模块文件夹名称
	->setHmvcModules(array(
	    'Demo' => 'demo'
	))
;

//启动，噪起来
Soter::run();


