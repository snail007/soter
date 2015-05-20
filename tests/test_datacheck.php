<?php

require_once 'pluginfortest.php';
//require_once('simpletest/web_tester.php');
require_once('simpletest/autorun.php');

//require_once('simpletest/browser.php');
/**
 * Soter测试案例
 * Test by snail（672308444@163.com）
 */
class TestDataCheck extends UnitTestCase {

	private $db;

	public function init() {

		$config = array(
		    'driverType' => 'mysql',
		    'database' => 'test',
		    'tablePrefix' => 'test_',
		    'debug' => true,
		    'slowQueryDebug' => FALSE,
		    'slowQueryTime' => 100, //单位毫秒，1秒=1000毫秒
		    'slowQueryHandle' => new Soter_Database_SlowQuery_Handle_Default(),
		    'indexDebug' => FALSE,
		    'minIndexType' => 'index',
		    'indexHandle' => new Soter_Database_Index_Handle_Default(),
		    'masters' => array(
			array(
			    'hostname' => '127.0.0.1',
			    'port' => 3306,
			    'username' => 'root',
			    'password' => 'admin'
			)
		    ),
		    'slaves' => array(
			array(
			    'hostname' => '127.0.0.1',
			    'port' => 3306,
			    'username' => 'root',
			    'password' => 'admin'
			)
		    )
		);
		$this->db = new Soter_Database_ActiveRecord($config);
		$aSql = 'CREATE TABLE `test_a` (`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(10) NOT NULL,`gid` int(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$this->clean();
		$this->assertTrue($this->db->execute($aSql));
	}

	public function clean() {
		$this->assertTrue($this->db->execute('DROP TABLE IF EXISTS test_a'));
	}

	public function testArray() {
		$data['test'] = 123;
		$this->assertFalse(Sr::checkData($data, array('test' => array('array' => '2')), $returnData, $errorMessage, $errorKey));
		$this->assertEqual($errorMessage, 2);
		$this->assertEqual($errorKey, 'test');

		$data['test'] = array();
		$this->assertTrue(Sr::checkData($data, array('test' => array('array' => '2')), $returnData, $errorMessage));
		$data['test'] = array(2, 1);
		$this->assertTrue(Sr::checkData($data, array('test' => array('array[2,2]' => '2')), $returnData, $errorMessage));
		$this->assertFalse(Sr::checkData($data, array('test' => array('array[3,4]' => '2')), $returnData, $errorMessage));
		$this->assertTrue(Sr::checkData($data, array('test' => array('array[0,2]' => '2')), $returnData, $errorMessage));
		$this->assertFalse(Sr::checkData($data, array('test' => array('array[3]' => '2')), $returnData, $errorMessage));
		$this->assertTrue(Sr::checkData($data, array('test' => array('array[2]' => '2')), $returnData, $errorMessage));
	}

	public function testnotArray() {
		$data['test'] = 123;
		$this->assertTrue(Sr::checkData($data, array('test' => array('notArray' => '2')), $returnData, $errorMessage));
		$data['test'] = array();
		$this->assertFalse(Sr::checkData($data, array('test' => array('notArray' => '2')), $returnData, $errorMessage));
	}

	public function testrequired() {
		$data['test'] = null;
		$this->assertFalse(Sr::checkData($data, array('test' => array('required' => '2')), $returnData, $errorMessage));
		$data['test'] = array();
		$this->assertFalse(Sr::checkData($data, array('test2' => array('required' => '2')), $returnData, $errorMessage));
		$data['test'] = 234;
		$this->assertTrue(Sr::checkData($data, array('test' => array('required' => '2')), $returnData, $errorMessage));
	}

	public function testrequiredKey() {
		$data['test'] = 123;
		$this->assertTrue(Sr::checkData($data, array('test' => array('requiredKey' => '2')), $returnData, $errorMessage));
		$data['test'] = array();
		$this->assertFalse(Sr::checkData($data, array('test' => array('requiredKey[user]' => '2')), $returnData, $errorMessage));
		$data['test'] = array();
		$data['user'] = array();
		$this->assertTrue(Sr::checkData($data, array('test' => array('requiredKey[user]' => '2')), $returnData, $errorMessage));
		$data['test'] = array();
		$data['user'] = array();
		$data['email'] = array();
		$this->assertTrue(Sr::checkData($data, array('test' => array('requiredKey[user,email]' => '2')), $returnData, $errorMessage));
		$this->assertFalse(Sr::checkData($data, array('test' => array('requiredKey[user,user2,email]' => '2')), $returnData, $errorMessage));
	}

	public function testdefault() {
		$data['test'] = null;
		$this->assertTrue(Sr::checkData($data, array('test' => array('default[1]' => '')), $returnData, $errorMessage));
		$this->assertEqual($returnData['test'], 1);

		$data['test'] = null;
		$this->assertTrue(Sr::checkData($data, array('test1' => array('default[1]' => ''), 'test2' => array('default[2]' => '')), $returnData, $errorMessage));
		$this->assertEqual($returnData['test1'], 1);
		$this->assertEqual($returnData['test2'], 2);
		$this->assertEqual($returnData['test'], null);
		$data['test'] = array('', '');
		$this->assertTrue(Sr::checkData($data, array('test' => array('default[1,2]' => '')), $returnData, $errorMessage));
		$this->assertEqual($returnData['test'][0], 1);
		$this->assertEqual($returnData['test'][1], 2);
		$data['test'] = array('2', '');
		$this->assertTrue(Sr::checkData($data, array('test' => array('default[1]' => '')), $returnData, $errorMessage));
		$this->assertEqual($returnData['test'][0], 2);
		$this->assertEqual($returnData['test'][1], 1);
	}

	public function testoptional() {
		$data['test'] = 123;
		$this->assertTrue(Sr::checkData($data, array('test2' => array('optional' => '2', 'array[2]' => '2')), $returnData, $errorMessage));
		$data['test'] = null;
		$this->assertTrue(Sr::checkData($data, array('test' => array('optional' => '2', 'array[2]' => '2')), $returnData, $errorMessage));
		$data['test'] = array();
		$this->assertFalse(Sr::checkData($data, array('test' => array('optional' => '', 'array[2]' => '2')), $returnData, $errorMessage));
		$this->assertEqual($errorMessage, 2);
	}

	public function testfunctions() {
		$data['test'] = ' 123 ';
		$this->assertTrue(Sr::checkData($data, array('test' => array('functions[trim,md5]' => '2')), $returnData, $errorMessage));
		$this->assertEqual($returnData['test'], md5(123));
		$this->assertTrue(Sr::checkData($data, array('test2' => array('functions[trim,md5]' => '2')), $returnData, $errorMessage));
		$this->assertEqual(!isset($returnData['test2']), true);
	}

	public function testxss() {
		$data['test'] = '<script></script>123';
		$this->assertTrue(Sr::checkData($data, array('test' => array('xss' => '')), $returnData, $errorMessage));
		$this->assertEqual($returnData['test'], 123);
	}

	public function testmatch() {
		$data['test'] = '123';
		$this->assertFalse(Sr::checkData($data, array('test' => array('match[test2]' => '')), $returnData, $errorMessage));
		$data['test2'] = '';
		$this->assertFalse(Sr::checkData($data, array('test' => array('match[test2]' => '')), $returnData, $errorMessage));
		$data['test2'] = '123';
		$this->assertTrue(Sr::checkData($data, array('test' => array('match[test2]' => '')), $returnData, $errorMessage));
	}

	public function testequal() {
		$data['test'] = '123';
		$this->assertFalse(Sr::checkData($data, array('test2' => array('equal[234]' => '')), $returnData, $errorMessage));
		$this->assertFalse(Sr::checkData($data, array('test' => array('equal[234]' => '')), $returnData, $errorMessage));
		$this->assertTrue(Sr::checkData($data, array('test' => array('equal[123]' => '')), $returnData, $errorMessage));
	}

	public function testenum() {
		$data['test'] = '0';
		$this->assertFalse(Sr::checkData($data, array('test2' => array('enum[2,3,4]' => '')), $returnData, $errorMessage));
		$data['test'] = '5';
		$this->assertFalse(Sr::checkData($data, array('test' => array('enum[2,3,4]' => '')), $returnData, $errorMessage));
		$data['test'] = '3';
		$this->assertTrue(Sr::checkData($data, array('test' => array('enum[1,2,3]' => '')), $returnData, $errorMessage));
	}

	public function testunique() {
		$this->init();
		$this->db->insertBatch('a', array(
		    array('name' => '001', 'gid' => 1),
		    array('name' => '002', 'gid' => 2),
		))->execute();
		$data['test'] = '001';
		$this->assertFalse(Sr::checkData($data, array('test' => array('unique[a.name]' => '')), $returnData, $errorMessage, $errorKey, $this->db));
		$data['test'] = '003';
		$this->assertTrue(Sr::checkData($data, array('test' => array('unique[a.name]' => '')), $returnData, $errorMessage, $errorKey, $this->db));
		$data['test'] = '001';
		$this->assertTrue(Sr::checkData($data, array('test' => array('unique[a.name,gid:1]' => '')), $returnData, $errorMessage, $errorKey, $this->db));
		$this->clean();
	}

	public function testexists() {
		$this->init();
		$this->db->insertBatch('a', array(
		    array('name' => '001', 'gid' => 1),
		    array('name' => '002', 'gid' => 2),
		))->execute();
		$data['test'] = '004';
		$this->assertFalse(Sr::checkData($data, array('test' => array('exists[a.name]' => '')), $returnData, $errorMessage, $errorKey, $this->db));
		$data['test'] = '001';
		$this->assertTrue(Sr::checkData($data, array('test' => array('exists[a.name]' => '')), $returnData, $errorMessage, $errorKey, $this->db));
		$data['test'] = '001';
		$this->assertTrue(Sr::checkData($data, array('test' => array('exists[a.name,gid:1]' => '')), $returnData, $errorMessage, $errorKey, $this->db));
		$this->clean();
	}

	public function testmin_len() {
		$data['test'] = 'aaa';
		$this->assertFalse(Sr::checkData($data, array('test2' => array('min_len[4]' => '')), $returnData, $errorMessage));
		$data['test'] = array('aaa', 'aaaa');
		$this->assertFalse(Sr::checkData($data, array('test' => array('min_len[4]' => '')), $returnData, $errorMessage));
		$data['test'] = 'aaa';
		$this->assertTrue(Sr::checkData($data, array('test' => array('min_len[2]' => '')), $returnData, $errorMessage));
		$data['test'] = array('aaa', 'aaaa');
		$this->assertTrue(Sr::checkData($data, array('test' => array('min_len[3]' => '')), $returnData, $errorMessage));
	}

	public function testmax_len() {
		$data['test'] = 'aaaaa';
		$this->assertFalse(Sr::checkData($data, array('test' => array('max_len[4]' => '')), $returnData, $errorMessage));
		$data['test'] = array('aaaaa', 'aaaaaa');
		$this->assertFalse(Sr::checkData($data, array('test' => array('max_len[4]' => '')), $returnData, $errorMessage));
		$data['test'] = 'aa';
		$this->assertTrue(Sr::checkData($data, array('test' => array('max_len[2]' => '')), $returnData, $errorMessage));
		$data['test'] = array('aaa', 'aaa');
		$this->assertTrue(Sr::checkData($data, array('test' => array('max_len[3]' => '')), $returnData, $errorMessage));
	}

	public function testrange_len() {
		$data['test'] = 'aaaaa';
		$this->assertFalse(Sr::checkData($data, array('test' => array('range_len[2,4]' => '')), $returnData, $errorMessage));
		$data['test'] = array('aaaaa', 'aaaaaa');
		$this->assertFalse(Sr::checkData($data, array('test' => array('range_len[4]' => '')), $returnData, $errorMessage));
		$data['test'] = 'aa';
		$this->assertTrue(Sr::checkData($data, array('test' => array('range_len[2,4]' => '')), $returnData, $errorMessage));
		$data['test'] = array('aaa', 'aaa');
		$this->assertTrue(Sr::checkData($data, array('test' => array('range_len[2,3]' => '')), $returnData, $errorMessage));
	}

	public function testlen() {
		$data['test'] = '';
		$this->assertFalse(Sr::checkData($data, array('test2' => array('len[0]' => '')), $returnData, $errorMessage));
		$data['test'] = 'a';
		$this->assertFalse(Sr::checkData($data, array('test' => array('len[2]' => '')), $returnData, $errorMessage));
		$data['test'] = 'a';
		$this->assertTrue(Sr::checkData($data, array('test' => array('len[1]' => '')), $returnData, $errorMessage));
		$data['test'] = array('aaaaaa', 'aaaaa');
		$this->assertFalse(Sr::checkData($data, array('test' => array('len[5]' => '')), $returnData, $errorMessage));
		$data['test'] = array('aaaaa', 'aaaaa');
		$this->assertTrue(Sr::checkData($data, array('test' => array('len[5]' => '')), $returnData, $errorMessage));
	}

	public function testmin() {
		$data['test'] = 'aaaaa';
		$this->assertFalse(Sr::checkData($data, array('test' => array('min[4]' => '')), $returnData, $errorMessage));
		$data['test'] = array(2, 10);
		$this->assertFalse(Sr::checkData($data, array('test' => array('min[4]' => '')), $returnData, $errorMessage));
		$data['test'] = 3;
		$this->assertTrue(Sr::checkData($data, array('test' => array('min[2]' => '')), $returnData, $errorMessage));
		$data['test'] = array(5, 6);
		$this->assertTrue(Sr::checkData($data, array('test' => array('min[3]' => '')), $returnData, $errorMessage));
	}

	public function testmax() {
		$data['test'] = 'aaaaa';
		$this->assertFalse(Sr::checkData($data, array('test' => array('max[4]' => '')), $returnData, $errorMessage));
		$data['test'] = array(2, 10);
		$this->assertFalse(Sr::checkData($data, array('test' => array('max[4]' => '')), $returnData, $errorMessage));
		$data['test'] = 1;
		$this->assertTrue(Sr::checkData($data, array('test' => array('max[2]' => '')), $returnData, $errorMessage));
		$data['test'] = array(1, 0);
		$this->assertTrue(Sr::checkData($data, array('test' => array('max[3]' => '')), $returnData, $errorMessage));
	}

	public function testrange() {
		$data['test'] = 'aaaaa';
		$this->assertFalse(Sr::checkData($data, array('test' => array('range[4,10]' => '')), $returnData, $errorMessage));
		$data['test'] = array(2, 10);
		$this->assertFalse(Sr::checkData($data, array('test' => array('range[4,10]' => '')), $returnData, $errorMessage));
		$data['test'] = 3;
		$this->assertTrue(Sr::checkData($data, array('test' => array('range[2,10]' => '')), $returnData, $errorMessage));
		$data['test'] = array(3, 10);
		$this->assertTrue(Sr::checkData($data, array('test' => array('range[3,10]' => '')), $returnData, $errorMessage));
	}

	public function testalpha() {
		$data['test'] = 'a2aaaa';
		$this->assertFalse(Sr::checkData($data, array('test' => array('alpha' => '')), $returnData, $errorMessage));
		$data['test'] = array('a2aaaa', 'bbb');
		$this->assertFalse(Sr::checkData($data, array('test' => array('alpha' => '')), $returnData, $errorMessage));
		$data['test'] = 'aaa';
		$this->assertTrue(Sr::checkData($data, array('test' => array('alpha' => '')), $returnData, $errorMessage));
		$data['test'] = array('aaa', 'bbb');
		$this->assertTrue(Sr::checkData($data, array('test' => array('alpha' => '')), $returnData, $errorMessage));
	}

	public function testalpha_num() {
		$data['test'] = 'a2aaaa;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('alpha_num' => '')), $returnData, $errorMessage));
		$data['test'] = array('a2aaa;a', 'bb3b');
		$this->assertFalse(Sr::checkData($data, array('test' => array('alpha_num' => '')), $returnData, $errorMessage));
		$data['test'] = 'aa3a';
		$this->assertTrue(Sr::checkData($data, array('test' => array('alpha_num' => '')), $returnData, $errorMessage));
		$data['test'] = array('a4aa', 'bb2b');
		$this->assertTrue(Sr::checkData($data, array('test' => array('alpha_num' => '')), $returnData, $errorMessage));
	}

	public function testalpha_dash() {
		$data['test'] = 'a2aaaa;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('alpha_dash' => '')), $returnData, $errorMessage));
		$data['test'] = array('a2aaa;a', 'bb3b');
		$this->assertFalse(Sr::checkData($data, array('test' => array('alpha_dash' => '')), $returnData, $errorMessage));
		$data['test'] = 'aa_-3a';
		$this->assertTrue(Sr::checkData($data, array('test' => array('alpha_dash' => '')), $returnData, $errorMessage));
		$data['test'] = array('a4-aa', 'bb2-_b');
		$this->assertTrue(Sr::checkData($data, array('test' => array('alpha_dash' => '')), $returnData, $errorMessage));
	}

	public function testalpha_start() {
		$data['test'] = '1a2aaaa;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('alpha_start' => '')), $returnData, $errorMessage));
		$data['test'] = array('a2aaa;a', '3bb3b');
		$this->assertFalse(Sr::checkData($data, array('test' => array('alpha_start' => '')), $returnData, $errorMessage));
		$data['test'] = 'aa_-3a';
		$this->assertTrue(Sr::checkData($data, array('test' => array('alpha_start' => '')), $returnData, $errorMessage));
		$data['test'] = array('a4-aa', 'bb2-_b');
		$this->assertTrue(Sr::checkData($data, array('test' => array('alpha_start' => '')), $returnData, $errorMessage));
	}

	public function testnum() {
		$data['test'] = '1a2aaaa;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('num' => '')), $returnData, $errorMessage));
		$data['test'] = array('2222', '3bb3b');
		$this->assertFalse(Sr::checkData($data, array('test' => array('num' => '')), $returnData, $errorMessage));
		$data['test'] = '333';
		$this->assertTrue(Sr::checkData($data, array('test' => array('num' => '')), $returnData, $errorMessage));
		$data['test'] = array('111', '222');
		$this->assertTrue(Sr::checkData($data, array('test' => array('num' => '')), $returnData, $errorMessage));
	}

	public function testint() {
		$data['test'] = '111.1;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('num' => '')), $returnData, $errorMessage));
		$data['test'] = array('22.22', '33');
		$this->assertFalse(Sr::checkData($data, array('test' => array('num' => '')), $returnData, $errorMessage));
		$data['test'] = '333';
		$this->assertTrue(Sr::checkData($data, array('test' => array('num' => '')), $returnData, $errorMessage));
		$data['test'] = array('111', '222');
		$this->assertTrue(Sr::checkData($data, array('test' => array('num' => '')), $returnData, $errorMessage));
	}

	public function testfloat() {
		$data['test'] = '1111;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('float' => '')), $returnData, $errorMessage));
		$data['test'] = array('2222', '3.3');
		$this->assertFalse(Sr::checkData($data, array('test' => array('float' => '')), $returnData, $errorMessage));
		$data['test'] = '33.3';
		$this->assertTrue(Sr::checkData($data, array('test' => array('float' => '')), $returnData, $errorMessage));
		$data['test'] = array('11.1', '22.2');
		$this->assertTrue(Sr::checkData($data, array('test' => array('float' => '')), $returnData, $errorMessage));
	}

	public function testnumeric() {
		$data['test'] = '1111a;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('numeric' => '')), $returnData, $errorMessage));
		$data['test'] = array('22a22', '3.3');
		$this->assertFalse(Sr::checkData($data, array('test' => array('numeric' => '')), $returnData, $errorMessage));
		$data['test'] = '33.3';
		$this->assertTrue(Sr::checkData($data, array('test' => array('numeric' => '')), $returnData, $errorMessage));
		$data['test'] = array('11.1', '22.2');
		$this->assertTrue(Sr::checkData($data, array('test' => array('numeric' => '')), $returnData, $errorMessage));
	}

	public function testnatural() {
		$data['test'] = '1111a;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('natural' => '')), $returnData, $errorMessage));
		$data['test'] = array('22a22', '3');
		$this->assertFalse(Sr::checkData($data, array('test' => array('natural' => '')), $returnData, $errorMessage));
		$data['test'] = '0';
		$this->assertTrue(Sr::checkData($data, array('test' => array('natural' => '')), $returnData, $errorMessage));
		$data['test'] = array('111', '222');
		$this->assertTrue(Sr::checkData($data, array('test' => array('natural' => '')), $returnData, $errorMessage));
	}

	public function testnatural_no_zero() {
		$data['test'] = '1111a;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('natural_no_zero' => '')), $returnData, $errorMessage));
		$data['test'] = array('0', '3');
		$this->assertFalse(Sr::checkData($data, array('test' => array('natural_no_zero' => '')), $returnData, $errorMessage));
		$data['test'] = '10';
		$this->assertTrue(Sr::checkData($data, array('test' => array('natural_no_zero' => '')), $returnData, $errorMessage));
		$data['test'] = array('111', '222');
		$this->assertTrue(Sr::checkData($data, array('test' => array('natural_no_zero' => '')), $returnData, $errorMessage));
	}

	public function testemail() {
		$data['test'] = '111@1.aaaaa;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('email' => '')), $returnData, $errorMessage));
		$data['test'] = array('111@1.aa', '1111.aaaa');
		$this->assertFalse(Sr::checkData($data, array('test' => array('email[true]' => '')), $returnData, $errorMessage));
		$data['test'] = '111@1.aaa';
		$this->assertTrue(Sr::checkData($data, array('test' => array('email' => '')), $returnData, $errorMessage));
		$data['test'] = '';
		$this->assertTrue(Sr::checkData($data, array('test' => array('email[true]' => '')), $returnData, $errorMessage));
		$data['test'] = array('111@1.aaa', '111@1.aaa');
		$this->assertTrue(Sr::checkData($data, array('test' => array('email' => '')), $returnData, $errorMessage));
	}

	public function testurl() {
		$data['test'] = '111@1.aaaaa;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('url' => '')), $returnData, $errorMessage));
		$data['test'] = array('111@1.aa', 'https://gitcode.com/');
		$this->assertFalse(Sr::checkData($data, array('test' => array('url[true]' => '')), $returnData, $errorMessage));
		$data['test'] = 'http://gitcode.com/';
		$this->assertTrue(Sr::checkData($data, array('test' => array('url' => '')), $returnData, $errorMessage));
		$data['test'] = '';
		$this->assertTrue(Sr::checkData($data, array('test' => array('url[true]' => '')), $returnData, $errorMessage));
		$data['test'] = array('http://gitcode.com/', 'https://gitcode.com/');
		$this->assertTrue(Sr::checkData($data, array('test' => array('url' => '')), $returnData, $errorMessage));
	}

	public function testqq() {
		$data['test'] = '111@1.aaaaa;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('qq' => '')), $returnData, $errorMessage));
		$data['test'] = array('252453445', 'https://gitcode.com/');
		$this->assertFalse(Sr::checkData($data, array('test' => array('qq[true]' => '')), $returnData, $errorMessage));
		$data['test'] = '252453445';
		$this->assertTrue(Sr::checkData($data, array('test' => array('qq' => '')), $returnData, $errorMessage));
		$data['test'] = '';
		$this->assertTrue(Sr::checkData($data, array('test' => array('qq[true]' => '')), $returnData, $errorMessage));
		$data['test'] = array('252453445', '2524543445');
		$this->assertTrue(Sr::checkData($data, array('test' => array('qq' => '')), $returnData, $errorMessage));
	}

	public function testphone() {
		$data['test'] = '111@1.aaaaa;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('phone' => '')), $returnData, $errorMessage));
		$data['test'] = array('252453445', 'https://gitcode.com/');
		$this->assertFalse(Sr::checkData($data, array('test' => array('phone[true]' => '')), $returnData, $errorMessage));
		$data['test'] = '010-78382323';
		$this->assertTrue(Sr::checkData($data, array('test' => array('phone' => '')), $returnData, $errorMessage));
		$data['test'] = '';
		$this->assertTrue(Sr::checkData($data, array('test' => array('phone[true]' => '')), $returnData, $errorMessage));
		$data['test'] = array('010-78358223', '0371-7838223');
		$this->assertTrue(Sr::checkData($data, array('test' => array('phone' => '')), $returnData, $errorMessage));
	}

	public function testmobile() {
		$data['test'] = '111@1.aaaaa;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('mobile' => '')), $returnData, $errorMessage));
		$data['test'] = array('18709676670', 'https://gitcode.com/');
		$this->assertFalse(Sr::checkData($data, array('test' => array('mobile[true]' => '')), $returnData, $errorMessage));
		$data['test'] = '18709676670';
		$this->assertTrue(Sr::checkData($data, array('test' => array('mobile' => '')), $returnData, $errorMessage));
		$data['test'] = '';
		$this->assertTrue(Sr::checkData($data, array('test' => array('mobile[true]' => '')), $returnData, $errorMessage));
		$data['test'] = array('18709676670', '18709675670');
		$this->assertTrue(Sr::checkData($data, array('test' => array('mobile' => '')), $returnData, $errorMessage));
	}

	public function testzipcode() {
		$data['test'] = '111@1.aaaaa;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('zipcode' => '')), $returnData, $errorMessage));
		$data['test'] = array('645600', 'https://gitcode.com/');
		$this->assertFalse(Sr::checkData($data, array('test' => array('zipcode[true]' => '')), $returnData, $errorMessage));
		$data['test'] = '645600';
		$this->assertTrue(Sr::checkData($data, array('test' => array('zipcode' => '')), $returnData, $errorMessage));
		$data['test'] = '';
		$this->assertTrue(Sr::checkData($data, array('test' => array('zipcode[true]' => '')), $returnData, $errorMessage));
		$data['test'] = array('645600', '145600');
		$this->assertTrue(Sr::checkData($data, array('test' => array('zipcode' => '')), $returnData, $errorMessage));
	}

	public function testidcard() {
		$data['test'] = '111@1.aaaaa;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('idcard' => '')), $returnData, $errorMessage));
		$data['test'] = array('32080119840313155X', 'https://gitcode.com/');
		$this->assertFalse(Sr::checkData($data, array('test' => array('idcard[true]' => '')), $returnData, $errorMessage));
		$data['test'] = '32080119840313155X';
		$this->assertTrue(Sr::checkData($data, array('test' => array('idcard' => '')), $returnData, $errorMessage));
		$data['test'] = '';
		$this->assertTrue(Sr::checkData($data, array('test' => array('idcard[true]' => '')), $returnData, $errorMessage));
		$data['test'] = array('32080119840313155X', '140481197509016877');
		$this->assertTrue(Sr::checkData($data, array('test' => array('idcard' => '')), $returnData, $errorMessage));
	}

	public function testip() {
		$data['test'] = '111@1.aaaaa;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('ip' => '')), $returnData, $errorMessage));
		$data['test'] = array('129.98.2.3', 'https://gitcode.com/');
		$this->assertFalse(Sr::checkData($data, array('test' => array('ip[true]' => '')), $returnData, $errorMessage));
		$data['test'] = '129.98.2.3';
		$this->assertTrue(Sr::checkData($data, array('test' => array('ip' => '')), $returnData, $errorMessage));
		$data['test'] = '';
		$this->assertTrue(Sr::checkData($data, array('test' => array('ip[true]' => '')), $returnData, $errorMessage));
		$data['test'] = array('129.98.2.3', '129.98.55.3');
		$this->assertTrue(Sr::checkData($data, array('test' => array('ip' => '')), $returnData, $errorMessage));
	}

	public function testchs() {
		$data['test'] = '111@1.a中文汉字aaaa;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('chs' => '')), $returnData, $errorMessage));
		$data['test'] = array('中文汉字', 'https://gitcode.com/');
		$this->assertFalse(Sr::checkData($data, array('test' => array('chs[true]' => '')), $returnData, $errorMessage));
		$data['test'] = '中文汉字';
		$this->assertTrue(Sr::checkData($data, array('test' => array('chs' => '')), $returnData, $errorMessage));
		$data['test'] = '';
		$this->assertTrue(Sr::checkData($data, array('test' => array('chs[true]' => '')), $returnData, $errorMessage));
		$data['test'] = array('中文汉字', '文汉字');
		$this->assertTrue(Sr::checkData($data, array('test' => array('chs' => '')), $returnData, $errorMessage));
		$data['test'] = '中文汉字';
		$this->assertTrue(Sr::checkData($data, array('test' => array('chs[false,4]' => '')), $returnData, $errorMessage));
		$data['test'] = '中文汉字';
		$this->assertFalse(Sr::checkData($data, array('test' => array('chs[false,2]' => '')), $returnData, $errorMessage));
		$data['test'] = array('中文汉字', '文汉字');
		$this->assertTrue(Sr::checkData($data, array('test' => array('chs[false,2,4]' => '')), $returnData, $errorMessage));
		$data['test'] = array('中文汉字', '文汉字');
		$this->assertTrue(Sr::checkData($data, array('test' => array('chs[false,2,]' => '')), $returnData, $errorMessage));
	}

	public function testdate() {
		$data['test'] = '111@1.aaaaa;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('date' => '')), $returnData, $errorMessage));
		$data['test'] = array('2015-12-03', 'https://gitcode.com/');
		$this->assertFalse(Sr::checkData($data, array('test' => array('date[true]' => '')), $returnData, $errorMessage));
		$data['test'] = '2015-12-03';
		$this->assertTrue(Sr::checkData($data, array('test' => array('date' => '')), $returnData, $errorMessage));
		$data['test'] = '';
		$this->assertTrue(Sr::checkData($data, array('test' => array('date[true]' => '')), $returnData, $errorMessage));
		$data['test'] = array('2015-12-03', '2015-12-04');
		$this->assertTrue(Sr::checkData($data, array('test' => array('date' => '')), $returnData, $errorMessage));
	}

	public function testdatetime() {
		$data['test'] = '111@1.aaaaa;';
		$this->assertFalse(Sr::checkData($data, array('test' => array('datetime' => '')), $returnData, $errorMessage));
		$data['test'] = array('2015-12-03 23:19:00', 'https://gitcode.com/');
		$this->assertFalse(Sr::checkData($data, array('test' => array('datetime[true]' => '')), $returnData, $errorMessage));
		$data['test'] = '2015-12-03 23:19:00';
		$this->assertTrue(Sr::checkData($data, array('test' => array('datetime' => '')), $returnData, $errorMessage));
		$data['test'] = '';
		$this->assertTrue(Sr::checkData($data, array('test' => array('datetime[true]' => '')), $returnData, $errorMessage));
		$data['test'] = array('2015-12-03 23:19:00', '2015-12-04 23:19:00');
		$this->assertTrue(Sr::checkData($data, array('test' => array('datetime' => '')), $returnData, $errorMessage));
	}

	public function testreg() {
		$data['test'] = '241a00';
		$this->assertFalse(Sr::checkData($data, array('test' => array('reg[/241A00/]' => '')), $returnData, $errorMessage));
		$data['test'] = array('a', 'A');
		$this->assertTrue(Sr::checkData($data, array('test' => array('reg[/a/i]' => '')), $returnData, $errorMessage));
	}

	public function testfgf() {
		$data['test'] = 'aaaaa';
		$this->assertFalse(Sr::checkData($data, array('test' => array('range[4#10]#' => '')), $returnData, $errorMessage));
		$data['test'] = array(2, 10);
		$this->assertFalse(Sr::checkData($data, array('test' => array('range[4#10]#' => '')), $returnData, $errorMessage));
		$data['test'] = 3;
		$this->assertTrue(Sr::checkData($data, array('test' => array('range[2#10]#' => '')), $returnData, $errorMessage));
		$data['test'] = array(3, 10);
		$this->assertTrue(Sr::checkData($data, array('test' => array('range[3#10]#' => '')), $returnData, $errorMessage));
	}

	public function testUserDefined() {
		$data['test'] = 'aaaaa';
		$this->assertTrue(Sr::checkData($data, array('test' => array('myRule' => '')), $returnData, $errorMessage));
	}

}
