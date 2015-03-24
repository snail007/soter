<?php

//数据库配置文件，文件名默认是database.php,
//也可以通过在入口文件里面修改配置：
//->setDatabseConfigFile('database')
//把里面的database参数修改为你想要的配置文件名即可
return array(
    //默认组
    'default_group' => 'mysql',
    //组名=>配置
    //mysql配置示例
    'mysql' => array(
	'driverType' => 'mysql',
	'debug' => true,
	'pconnect' => true,
	'charset' => 'utf8',
	'collate' => 'utf8_general_ci',
	'database' => '',
	'tablePrefix' => '',
	'tablePrefixSqlIdentifier' => '_tablePrefix_',
	'slowQueryTime' => 3000, //单位毫秒，1秒=1000毫秒
	'slowQueryHandle' => new Soter_Database_SlowQuery_Handle_Default(),
	'nonUsingIndexQueryHandle' => new Soter_Database_NonUsingIndexQuery_Handle_Default(),
	'masters' => array(
	    'master01' => array(
		'hostname' => '127.0.0.1',
		'port' => 3306,
		'username' => 'root',
		'password' => '',
	    )
	),
	'slaves' => array(
//		    'slave01' => array(
//			'hostname' => '127.0.0.1',
//			'port' => 3306,
//			'username' => 'root',
//			'password' => '',
//		    )
	)
    ),
    //sqlite3配置示例
    'sqlite3' => array(
	'driverType' => 'sqlite',
	'debug' => true,
	'pconnect' => true,
	'masters' => array(
	    'master01' => array(
		'hostname' => 'test.sqlite3', //sqlite3数据库路径
	    )
	),
	'slaves' => array(
//		    'slave01' => array(
//		    )
	)
    )
	)
;
