<?php

/**
 * Session托管配置，如果要使用哪一种托管，取消相应的return部分的注释即可。
 * 提示：不要存在两个或者以上的return
 */
/**
 * Memcache托管
 */
//return new Soter_Session_Memcache(array('path' => 'tcp://127.0.0.1:11211'));
/**
 * Memcached托管
 */
//return new Soter_Session_Memcached(array('path' => '127.0.0.1:11211'));
/**
 * Redis托管
 */
//return new Soter_Session_Redis(array('path' => 'tcp://127.0.0.1:6379'));
/**
 * Mongodb托管
 */
/* 
return new Soter_Session_Mongodb(array(
    'host' => '127.0.0.1', //mongodb主机地址
    'port' => 27017, //端口
    'user' => 'root',
    'password' => '',
    'database' => 'local', //   MongoDB 数据库名称
    'collection' => 'sessions', //   MongoDB collection名称
    'persistent' => false, // 是否持久连接
    'persistentId' => 'SoterMongoSession', // 持久连接id
    // 是否支持 replicaSet
    'replicaSet' => false,
	)
);
*/
/**
 * MySQL托管
 * 表结构如下：
CREATE TABLE `session_handler_table` (
`id` varchar(255) NOT NULL,
`data` mediumtext NOT NULL,
`timestamp` int(255) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `id` (`id`,`timestamp`),
KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 */
/* 
return new Soter_Session_Mysql(array(
    //如果使用数据库配置里面的组信息，这里可以设置group组名称，没有就留空
    //设置group组名称后，下面连接的配置不再起作用，group优先级大于下面的连接信息
    'group' => '',
     //表全名，不包含前缀
    'table' => 'session_handler_table',
    //表前缀，如果有使用数据库配置组里面的信息
    //这里可以设置相同的数据库配置组里面的前缀才能正常工作
    'table_prefix' => '', 
    //连接信息
    'hostname' => '127.0.0.1',
    'port' => 3306,
    'username' => 'root',
    'password' => 'admin',
    'database' => 'test',
	)
);
*/