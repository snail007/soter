<?php

/**
 * 缓存配置，如果要使用哪一种缓存，取消相应的return部分的注释即可。
 * 提示：不要存在两个或者以上的return
 */
return array(
    'default_type' => 'file', //默认的缓存类型，值是下面drivers关联数组的键名称。
    'drivers' => array(
	'file' => Sr::config()->getPrimaryApplicationDir() . 'storage/cache/', //缓存文件保存路径
	'memcache' => array(//memcache服务器信息，支持多个
	    array("127.0.0.1", 11211),
	//array("new.host.ip",11211),
	),
	'memcached' => array(//memcached服务器信息，支持多个
	    array("127.0.0.1", 11211),
	//array("new.host.ip",11211),
	),
	'apc' => '', //apc缓存不需要配置信息，保持null即可
	'redis' => array(//redis服务器信息，支持多主多从。原理是：写的时候每个主都写，读的时候随机一个从读取数据
	    'masters' => array(
		'master01' => array(
		    //sock,tcp;连接类型，tcp：使用host port连接，sock：本地sock文件连接
		    'type' => 'tcp',
		    //key的前缀，便于管理查看，在set和get的时候会自动加上和去除前缀，无前缀请保持null
		    'prefix' => Sr::server('HTTP_HOST'),
		    //type是sock的时候，需要在这里指定sock文件的完整路径
		    'sock' => '',
		    //type是tcp的时候，需要在这里指定host，port，password，timeout，retry
		    //主机地址
		    'host' => '127.0.0.1',
		    //端口
		    'port' => 6379,
		    //密码，如果没有,保持null
		    'password' => NULL,
		    //0意味着没有超时限制，单位秒
		    'timeout' => 0,
		    //连接失败后的重试时间间隔，单位毫秒
		    'retry' => 100,
		    // 数据库序号，默认0, 参考 http://redis.io/commands/select
		    'db' => 0,
		),
	    ),
	    'slaves' => array(
	    //	'slave01' => array(
	    //	),
	    ),
	)
    )
);
