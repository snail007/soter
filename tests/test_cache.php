<?php

require_once 'pluginfortest.php';
//require_once('simpletest/web_tester.php');
require_once('simpletest/autorun.php');

//require_once('simpletest/browser.php');
/**
 * Soter测试案例
 * Test by snail（672308444@163.com）
 */
class TestCache extends UnitTestCase {

	public function testMemcache() {
		if (class_exists('Memcache', FALSE)) {
			$cache = new Soter_Cache_Memcache(array(
			    //$name $port
			    array("127.0.0.1", 11211),
				//array("new.host.ip",11211),
			));
			$this->assertTrue($cache->set('test', 'testvalue', 1));
			$this->assertEqual($cache->get('test'), 'testvalue');
			$this->assertTrue($cache->delete('test'));
			$this->assertFalse($cache->get('test'));
			$this->assertTrue($cache->set('test', 'testvalue', 1));
			$this->assertTrue($cache->clean());
			$this->assertFalse($cache->get('test'));
			$this->assertTrue($cache->set('test', 'testvalue', 1));
			sleep(2);
			$this->assertEqual($cache->get('test'), null);
			$this->assertIsA($cache->instance(), 'Memcache');
		}
	}

	public function testMemcached() {
		if (class_exists('Memcached', FALSE)) {
			$cache = new Soter_Cache_Memcached(array(
			    //$name $port $sharing
			    array("127.0.0.1", 11211, 1),
				//array("new.host.ip",11211,1),
			));
			$this->assertTrue($cache->set('test', 'testvalue', 1));
			$this->assertEqual($cache->get('test'), 'testvalue');
			$this->assertTrue($cache->delete('test'));
			$this->assertFalse($cache->get('test'));
			$this->assertTrue($cache->set('test', 'testvalue', 1));
			$this->assertTrue($cache->clean());
			$this->assertFalse($cache->get('test'));
			$this->assertTrue($cache->set('test', 'testvalue', 1));
			sleep(2);
			$this->assertEqual($cache->get('test'), null);
			$this->assertIsA($cache->instance(), 'Memcached');
		}
	}

//
	public function testApc() {
		if (function_exists('apc_store')) {
			$cache = new Soter_Cache_Apc();
			$this->assertTrue($cache->set('test', 'testvalue', 1));
			$this->assertEqual($cache->get('test'), 'testvalue');
			$this->assertTrue($cache->delete('test'));
			$this->assertFalse($cache->get('test'));
			$this->assertTrue($cache->set('test', 'testvalue', 1));
			$this->assertTrue($cache->clean());
			$this->assertFalse($cache->get('test'));
			$this->assertReference($cache, $cache->instance());
			$this->assertIsA($cache->instance(), 'Soter_Cache_Apc');
			//$this->assertTrue($cache->set('test', 'testvalue', 1));
			//sleep(1);
			//$this->assertEqual($cache->get('test'), null);
		}
	}

	public function testFile() {
		$cache = new Soter_Cache_File();
		$this->assertTrue($cache->set('test', 'testvalue', 1));
		$this->assertEqual($cache->get('test'), 'testvalue');
		$this->assertTrue($cache->delete('test'));
		$this->assertFalse($cache->get('test'));
		$this->assertTrue($cache->set('test', 'testvalue', 1));
		$this->assertTrue($cache->clean());
		$this->assertFalse($cache->get('test'));
		$this->assertTrue($cache->set('test', 'testvalue', 1));
		sleep(2);
		$this->assertEqual($cache->get('test'), null);
		$this->assertReference($cache, $cache->instance());
		$this->assertIsA($cache->instance(), 'Soter_Cache_File');
	}

	public function testRedis() {
		$cache = new Soter_Cache_Redis(array(
		    //redis服务器信息，支持集群。
		    //原理是：读写的时候根据算法sprintf('%u',crc32($key))%count($nodeCount)
		    //把$key分散到下面不同的master服务器上，负载均衡，而且还支持单个key的主从负载均衡。
		    array(
			'master' => array(
			    //sock,tcp;连接类型，tcp：使用host port连接，sock：本地sock文件连接
			    'type' => 'tcp',
			    //key的前缀，便于管理查看，在set和get的时候会自动加上和去除前缀，无前缀请保持null
			    'prefix' => null, //Sr::server('HTTP_HOST')
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
			    'timeout' => 3000,
			    //连接失败后的重试时间间隔，单位毫秒
			    'retry' => 100,
			    // 数据库序号，默认0, 参考 http://redis.io/commands/select
			    'db' => 0,
			),
			'slaves' => array(
//			array(
//			    'type' => 'tcp',
//			    'prefix' => null, //Sr::server('HTTP_HOST')
//			    'sock' => '',
//			    'host' => '127.0.0.1',
//			    'port' => 6380,
//			    'password' => NULL,
//			    'timeout' => 3000,
//			    'retry' => 100,
//			    'db' => 0,
//			),
			)
		    ),
//		array(
//		    'master' => array(
//			'type' => 'tcp',
//			'prefix' => null,
//			'sock' => '',
//			'host' => '10.69.112.34',
//			'port' => 6379,
//			'password' => NULL,
//			'timeout' => 3000,
//			'retry' => 100,
//			'db' => 0,
//		    ),
//		    'slaves' => array(
//		    )
//		),
		));
		$this->assertTrue($cache->set('test', 'testvalue', 1));
		$this->assertEqual($cache->get('test'), 'testvalue');
		$this->assertTrue($cache->delete('test'));
		$this->assertFalse($cache->get('test'));
		$this->assertTrue($cache->set('test', 'testvalue', 1));
		$this->assertTrue($cache->clean());
		$this->assertFalse($cache->get('test'));
		$this->assertTrue($cache->set('test', 'testvalue', 1));
		sleep(2);
		$this->assertEqual($cache->get('test'), null);
		$this->assertIsA($cache->instance(), 'Redis');
	}

	public function testRedisCluster() {
		if (class_exists('RedisCluster', FALSE)) {
			$cache = new Soter_Cache_Redis_Cluster(array(
			    'hosts' => array(//集群中所有master主机信息
				'127.0.0.1:7001',
				'127.0.0.1:7002',
				'127.0.0.1:7003',
			    ),
			    'timeout' => 1.5, //连接超时，单位秒
			    'read_timeout' => 1.5, //读超时，单位秒
			    'persistent' => false//是否持久化连接
				)
			);
			$this->assertTrue($cache->set('test', 'testvalue', 1));
			$this->assertEqual($cache->get('test'), 'testvalue');
			$this->assertTrue($cache->delete('test'));
			$this->assertFalse($cache->get('test'));
			$this->assertTrue($cache->set('test', 'testvalue', 1));
			$this->assertTrue($cache->clean('test'));
			$this->assertFalse($cache->get('test'));
			$this->assertTrue($cache->set('test', 'testvalue', 1));
			sleep(2);
			$this->assertEqual($cache->get('test'), null);
			$this->assertIsA($cache->instance(), 'RedisCluster');
		}
	}

	public function testSrCache() {
		Sr::config()->setCacheConfig('cache');
		$this->assertIsA(Sr::cache('file'), 'Soter_Cache_File');
		$this->assertIsA(Sr::cache('apc'), 'Soter_Cache_Apc');
		$this->assertIsA(Sr::cache('memcache'), 'Soter_Cache_Memcache');
		$this->assertIsA(Sr::cache('memcached'), 'Soter_Cache_Memcached');
		$this->assertIsA(Sr::cache('redis'), 'Soter_Cache_Redis');
		$this->assertIsA(Sr::cache('my_cache'), 'Cache_MyCache');

		$this->assertIsA(Sr::cache(), 'Soter_Cache_File');

		$this->assertIsA(Sr::cache(array(
			    'class' => 'Soter_Cache_File',
			    //缓存文件保存路径
			    'config' => Sr::config()->getStorageDirPath() . 'cache/'
			)), 'Soter_Cache_File');
		$this->assertIsA(Sr::cache(array(
			    'class' => 'Soter_Cache_Memcache',
			    'config' => array(//memcache服务器信息，支持多个
				array("127.0.0.1", 11211),
			    //array("new.host.ip",11211),
			    )
			)), 'Soter_Cache_Memcache');
		$this->assertIsA(Sr::cache(array(
			    'class' => 'Soter_Cache_Memcached',
			    'config' => array(//memcached服务器信息，支持多个
				array("127.0.0.1", 11211),
			    //array("new.host.ip",11211),
			    )
			)), 'Soter_Cache_Memcached');
		$this->assertIsA(Sr::cache(array(
			    'class' => 'Soter_Cache_Apc',
			    'config' => NULL//apc缓存不需要配置信息，保持null即可
			)), 'Soter_Cache_Apc');
		$this->assertIsA(Sr::cache(
				array(
				    'class' => 'Soter_Cache_Redis',
				    'config' => array(
					//redis服务器信息，支持多主多从。原理是：写的时候每个主都写，读的时候随机一个从读取数据
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
			)), 'Soter_Cache_Redis');
		$a = Sr::cache(array(
			    'class' => 'Soter_Cache_Apc',
			    'config' => NULL//apc缓存不需要配置信息，保持null即可
		));
		$b = Sr::cache('apc');
		$this->assertCopy($a, $b);
		$a = Sr::cache('my_cache');
		$b = Sr::cache(array(
			    'class' => 'Cache_MyCache',
			    'config' => null
		));
		$this->assertCopy($a, $b);
		$this->assertIsA(Sr::cache(array(
			    'class' => 'Cache_MyCache',
			    'config' => null
			)), 'Cache_MyCache');
	}

}
