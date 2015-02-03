<?php

##{copyright}##
define("IN_SOTER", true);
//引入核心
require dirname(__FILE__) . '/soter.php';

define('SOTER_APP_PATH', Sr::realPath(dirname(__FILE__) . '/../application') . '/');

define('SOTER_PACKAGES_PATH', SOTER_APP_PATH . 'packages/');

//初始化系统配置
Soter::initialize()
	//项目目录路径
	->setApplicationDir(SOTER_APP_PATH)
	//注册拓展包
	->addPackages(array(
	    //SOTER_PACKAGES_PATH . 'misc',
	))
	//入口文件所在目录
	->setIndexDir(dirname(__FILE__) . '/')
	//入口文件名称
	->setIndexName(pathinfo(__FILE__, PATHINFO_BASENAME))	
	//初始化请求
	->setRequest(new Soter_Request(Sr::arrayGet($_SERVER, 'REQUEST_URI')))
	//默认路由器
	->addRouter(new Soter_Default_Router_PathInfo())
	//->addLoggerWriter(new Logger_MyWriter())
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
	//hvmc模块，key是url里面的模块名称，值是模块目录名称
	->setHmvcModules(array('Demo' => 'demo'))
	
;

//运行
if (Sr::isCli()) {
	
} else {
	//启动，噪起来
	Soter::run();
}



