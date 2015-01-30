<?php

##{copyright}##
define("IN_SOTER", true);
//入口文件所在目录
define("SOTER_DIR", dirname(__FILE__) . '/');
//项目目录
define("SOTER_APP_DIR", SOTER_DIR . '/../application/');
//引入核心
require SOTER_DIR . 'soter.php';

//初始化
Soter::initialize()
	//获取配置
	->getConfig()
	//获取配置程序目录
	->setApplicationPath(SOTER_APP_DIR)
	//初始化请求
	->setRequest(new Soter_Request(S::arrayGet($_SERVER, 'REQUEST_URI')))
	//默认路由器
	->addRouter(new Soter_Router());

//运行
if (Soter_Environment::isCli()) {
	
} else {
	//启动，噪起来
	Soter::run();
}



