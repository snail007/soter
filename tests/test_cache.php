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
		}
	}

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
			$this->assertTrue($cache->set('test', 'testvalue', 1));
			sleep(3);
			$this->assertEqual($cache->get('test'), null);
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
	}

	public function testRedis() {
		$cache = new Soter_Cache_Redis(array(
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

		$this->assertIsA(Sr::cache(array('file' => Sr::config()->getPrimaryApplicationDir() . 'storage/cache/')), 'Soter_Cache_File');
		$this->assertIsA(Sr::cache(array('memcache' => array(//memcache服务器信息，支持多个
				array("127.0.0.1", 11211),
			    //array("new.host.ip",11211),
		    ))), 'Soter_Cache_Memcache');
		$this->assertIsA(Sr::cache(array('memcached' => array(//memcached服务器信息，支持多个
				array("127.0.0.1", 11211),
			    //array("new.host.ip",11211),
		    ))), 'Soter_Cache_Memcached');
		$this->assertIsA(Sr::cache(array('apc' => null)), 'Soter_Cache_Apc');
		$this->assertIsA(Sr::cache(array('redis' => array(//redis服务器信息，支持多主多从。原理是：写的时候每个主都写，读的时候随机一个从读取数据
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
		    ))), 'Soter_Cache_Redis');
		$a = Sr::cache(array('apc' => null));
		$b = Sr::cache('apc');
		$this->assertCopy($a, $b);
		$a=Sr::cache('my_cache');
		$b=Sr::cache(array(
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
