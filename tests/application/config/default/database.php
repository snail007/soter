<?php

//数据库配置文件，文件名默认是database.php,
//也可以通过在入口文件里面修改配置：
//->setDatabseConfig('database')
//把里面的database参数修改为你想要的配置文件名即可
return
	array(
	    //默认组
	    'default_group' => 'mysql',
	    //组名=>配置
	    //mysql配置示例
	    'mysql' => array(
		'driverType' => 'mysql',
		'debug' => true,
		'pconnect' => false,
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci',
		'database' => 'test',
		'tablePrefix' => '',
		'tablePrefixSqlIdentifier' => '_tablePrefix_',
		/**
		 * 因为mysql从5.6开始explain才支持SELECT DELETE INSERT REPLACE UPDATE五种类型
		 * 5.5及之前版本explain只支持SELECT类型语句。
		 * 当你启用了下面的“慢查询记录”或者“索引类型的查询记录”，它们需执行explain语句，
		 * 为了开发的时候更好的分析程序查询性能，这里务必设置好你要连接的mysql的版本是否大于5.6
		 */
		'versionThan56' => false,//mysql的版本是否大于5.6，true：大于，false：小于
		//是否开启慢查询记录
		'slowQueryDebug' => false,
		'slowQueryTime' => 3000, //慢查询最小时间,单位毫秒，1秒=1000毫秒
		'slowQueryHandle' => new Soter_Database_SlowQuery_Handle_Default(),
		/**
		 * 是否开启没有满足设置的索引类型的查询记录
		 */
		'indexDebug' => false,
		/**
		 * 索引使用的最小情况，只有小于最小情况的时候才会记录sql到日志
		 * minIndexType值从好到坏依次是:
		 * system > const > eq_ref > ref > fulltext > ref_or_null 
		 * > index_merge > unique_subquery > index_subquery > range 
		 * > index > ALL 一般来说，得保证查询至少达到range级别，最好能达到ref
		 * 避免ALL即全表扫描
		 */
		'minIndexType' => 'index',
		'indexHandle' => new Soter_Database_Index_Handle_Default(),
		'masters' => array(
		    'master01' => array(
			'hostname' => '127.0.0.1',
			'port' => 3306,
			'username' => 'root',
			'password' => 'admin',
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
		'pconnect' => false,
		'tablePrefix' => '',
		'tablePrefixSqlIdentifier' => '_tablePrefix_',
		'database' => 'test.sqlite3', //sqlite3数据库路径
		//是否开启慢查询记录
		'slowQueryDebug' => true,
		'slowQueryTime' => 3000, //单位毫秒，1秒=1000毫秒
		'slowQueryHandle' => new Soter_Database_SlowQuery_Handle_Default()
	    )
	)
;
