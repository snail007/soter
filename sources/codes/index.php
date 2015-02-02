<?php

##{copyright}##
define("IN_SOTER", true);
//引入核心
require dirname(__FILE__) . '/soter.php';

//初始化系统配置
Soter::initialize()
	//入口文件所在目录
	->setIndexDir(dirname(__FILE__) . '/')
	//入口文件名称
	->setIndexName(pathinfo(__FILE__, PATHINFO_BASENAME))
	//项目目录
	->setApplicationDir(dirname(__FILE__) . '/../application/')
	//初始化请求
	->setRequest(new Soter_Request(Sr::arrayGet($_SERVER, 'REQUEST_URI')))
	//默认路由器
	->addRouter(new Soter_Default_Router_PathInfo());


//运行
if (Sr::isCli()) {
    
} else {
    //启动，噪起来
    Soter::run();
}



