<?php

/**
 * Redis缓存支持多主多从，如果只有一个，设置一个master01，保持slaves为空即可。
 * 原理是：写操作会在所有的主上面写，获取数据会随机使用一个从。
 */
return array(
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
);
