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
		$cache = new Soter_Cache_Memcache('cache/memcache');
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
			$cache = new Soter_Cache_Memcached('cache/memcached');
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
			sleep(2);
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
		$cache = new Soter_Cache_Redis('cache/redis');
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
