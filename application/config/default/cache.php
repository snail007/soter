<?php
/**
 * 缓存配置，如果要使用哪一种缓存，取消相应的return部分的注释即可。
 * 提示：不要存在两个或者以上的return
 */

/**
 * 文件缓存
 */
//return new Soter_Cache_File(Sr::config()->getPrimaryApplicationDir() . 'storage/cache/');

/**
 * Memcache缓存
 */
/*
return new Soter_Cache_Memcache(
	array(
    //$name $port
    array("127.0.0.1", 11211),
	//array("new.host.ip",11211),
	)
);
*/

/**
 * Memcached缓存
 */
/*
return new Soter_Cache_Memcached(array(
    //$name $port $sharing
    array("127.0.0.1", 11211, 1),
	//array("new.host.ip",11211,1),
	)
);
 */ 
/**
 * Apc缓存
 */
//return new Soter_Cache_Apc();
/**
 * Redis缓存
 */
/*
return new Soter_Cache_Redis(
	array(
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
);
*/