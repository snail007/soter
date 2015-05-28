<?php

require_once 'pluginfortest.php';
//require_once('simpletest/web_tester.php');
require_once('simpletest/autorun.php');

//require_once('simpletest/browser.php');
/**
 * Soter测试案例
 * Test by snail（672308444@163.com）
 */
class TestDao extends UnitTestCase {

	private $db, $dao;

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
		$this->dao = Sr::factory('Dao_TestADao');
		$this->dao->setDb($this->db);
		$this->clean();
		$this->assertTrue($this->db->execute($aSql));
	}

	public function clean() {
		$this->assertTrue($this->db->execute('DROP TABLE IF EXISTS test_a'));
	}

	public function testInsert() {
		$this->init();
		$id = $this->dao->insert(array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)));
		$this->assertEqual($id, 1);
		$id = $this->dao->insert(array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)));
		$this->assertEqual($id, 2);
		$this->clean();
	}

	public function testinsertBatch() {
		$this->init();
		$id = $this->dao->insert(array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)));
		$this->assertEqual($id, 1);
		$id = $this->dao->insert(array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)));
		$this->assertEqual($id, 2);
		$id = $this->dao->insertBatch(array(array('name' => 'name3', 'gid' => rand(1000, 10000)), array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000))));
		$this->assertEqual($id, 3);
		$row = $this->dao->find(3);
		$this->assertEqual($row['name'], 'name3');
		$this->clean();
	}

	public function testUpdate() {
		$this->init();
		$id = $this->dao->insert(array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)));
		$this->assertEqual($id, 1);
		$id = $this->dao->insert(array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)));
		$this->assertEqual($id, 2);
		$num = $this->dao->update(array('name' => 'name22', 'gid' => rand(1000, 10000)), 2);
		$this->assertEqual($num, 1);
		$this->assertEqual('name22', $this->dao->findCol('name', array('id' => 2)));
		$num = $this->dao->update(array('name' => 'name33', 'gid' => rand(1000, 10000)), array('id' => 2));
		$this->assertEqual($num, 1);
		$this->assertEqual('name33', $this->dao->findCol('name', array('id' => 2)));
		$this->clean();
	}

	public function testupdateBatch() {
		$this->init();
		$id = $this->dao->insert(array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)));
		$this->assertEqual($id, 1);
		$id = $this->dao->insert(array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)));
		$this->assertEqual($id, 2);
		$num = $this->dao->updateBatch(array(
		    array('id' => 1, 'name' => 'name1'), 
		    array('id' => 2, 'name' => 'name2')
		    ),'id');
		$this->assertEqual($num, 2);
		$row = $this->dao->find(1);
		$this->assertEqual($row['name'], 'name1');
		$row = $this->dao->find(2);
		$this->assertEqual($row['name'], 'name2');
		$this->clean();
	}

	public function testDelete() {
		$this->init();
		$num = $this->dao->insert(array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)));
		$this->assertEqual($num, 1);
		$num = $this->dao->insert(array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)));
		$this->assertEqual($num, 2);
		$num = $this->dao->insert(array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)));
		$this->assertEqual($num, 3);
		$num = $this->dao->delete(1);
		$this->assertEqual($num, 1);
		$num = $this->dao->delete(array(2, 10));
		$this->assertEqual($num, 1);
		$num = $this->dao->delete(3, array('gid <>' => 0));
		$this->assertEqual($num, 1);
		$this->clean();
	}

	public function testFind() {
		$this->init();
		$num = $this->dao->insert(array('name' => 'name11', 'gid' => rand(1000, 10000)));
		$this->assertEqual($num, 1);
		$num = $this->dao->insert(array('name' => 'name22', 'gid' => rand(1000, 10000)));
		$this->assertEqual($num, 2);
		$num = $this->dao->insert(array('name' => 'name33', 'gid' => rand(1000, 10000)));
		$this->assertEqual($num, 3);
		$num = $this->dao->find(2);
		$this->assertEqual($num['name'], 'name22');
		$num = $this->dao->find(array(2, 10));
		$this->assertEqual($num['name'], 'name22');
		$num = $this->dao->find(array('id' => 2));
		$this->assertEqual($num['name'], 'name22');
		$num = $this->dao->find(array('id' => 2), true);
		$this->assertEqual($num[0]['name'], 'name22');
		$num = $this->dao->find(array(1, 3), true, array('id' => 'desc'));
		$this->assertEqual($num[0]['name'], 'name33');
		$num = $this->dao->find(array('id >=' => 2), array('id' => 'asc'));
		$this->assertEqual($num[0]['name'], 'name22');
		$this->clean();
	}

	public function testFindCol() {
		$this->init();
		$num = $this->dao->insert(array('name' => 'name11', 'gid' => rand(1000, 10000)));
		$this->assertEqual($num, 1);
		$num = $this->dao->insert(array('name' => 'name22', 'gid' => rand(1000, 10000)));
		$this->assertEqual($num, 2);
		$num = $this->dao->insert(array('name' => 'name33', 'gid' => rand(1000, 10000)));
		$this->assertEqual($num, 3);
		$num = $this->dao->findCol('name', 2);
		$this->assertEqual($num, 'name22');
		$num = $this->dao->findCol('name', array(2, 10));
		$this->assertEqual($num, 'name22');
		$num = $this->dao->findCol('name', array('id' => 2));
		$this->assertEqual($num, 'name22');
		$num = $this->dao->findCol('name', array('id' => 2), true);
		$this->assertEqual($num[0], 'name22');
		$num = $this->dao->findCol('name', array(1, 3), true, array('id' => 'desc'));
		$this->assertEqual($num[0], 'name33');
		$num = $this->dao->findCol('name', array('id >=' => 2), array('id' => 'asc'));
		$this->assertEqual($num[0], 'name22');
		$this->clean();
	}

	public function testFindAll() {
		$this->init();
		$num = $this->dao->insert(array('name' => 'name11', 'gid' => rand(1000, 10000)));
		$this->assertEqual($num, 1);
		$num = $this->dao->insert(array('name' => 'name22', 'gid' => rand(1000, 10000)));
		$this->assertEqual($num, 2);
		$num = $this->dao->insert(array('name' => 'name33', 'gid' => rand(1000, 10000)));
		$this->assertEqual($num, 3);
		$num = $this->dao->findAll();
		$this->assertEqual(count($num), 3);
		$num = $this->dao->findAll(array('id >=' => 2));
		$this->assertEqual(count($num), 2);
		$num = $this->dao->findAll(array('id >=' => 2), array('id' => 'desc'));
		$this->assertEqual($num[1]['name'], 'name22');
		$num = $this->dao->findAll(array('id >=' => 1), array('id' => 'desc'), 2);
		$this->assertEqual($num[1]['name'], 'name22');
		$num = $this->dao->findAll(array('id >=' => 1), array('id' => 'desc'), 2, 'id,name');
		$this->assertEqual($num[1]['name'], 'name22');
		$this->assertFalse(isset($num[1]['gid']));
		$this->clean();
	}

	public function testGetPage() {
		$this->init();
		$num = $this->dao->insert(array('name' => 'name11', 'gid' => rand(1000, 10000)));
		$this->assertEqual($num, 1);
		$num = $this->dao->insert(array('name' => 'name22', 'gid' => rand(1000, 10000)));
		$this->assertEqual($num, 2);
		$num = $this->dao->insert(array('name' => 'name33', 'gid' => rand(1000, 10000)));
		$this->assertEqual($num, 3);
		$num = $this->dao->getPage(1, 1, '#', '*', array('id' => array(1, 2, 3)), array('id' => 'desc'), array(1, 2, 3, 4, 5, 6), 10);
		$this->assertEqual(count($num['items']), 1);
		$this->assertTrue(!empty($num['page']));
		$this->clean();
	}

	public function testSearch() {
		$this->init();
		$num = $this->dao->insert(array('name' => 'name11', 'gid' => rand(1000, 10000)));
		$this->assertEqual($num, 1);
		$num = $this->dao->insert(array('name' => 'name22', 'gid' => rand(1000, 10000)));
		$this->assertEqual($num, 2);
		$num = $this->dao->insert(array('name' => 'name33', 'gid' => rand(1000, 10000)));
		$this->assertEqual($num, 3);
		$num = $this->dao->search(1, 2, '#', '*', 'id>?', array(0), array(1, 2, 3, 4, 5, 6), 10);
		$this->assertEqual(count($num['items']), 2);
		$this->assertTrue(!empty($num['page']));
		$this->clean();
	}

}
