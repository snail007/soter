<?php

require_once 'pluginfortest.php';
//require_once('simpletest/web_tester.php');
require_once('simpletest/autorun.php');

//require_once('simpletest/browser.php');
/**
 * Soter测试案例
 * Test by snail（672308444@163.com）
 */
class testDbSqlite extends UnitTestCase {

	private $db;

	public function setUp() {
		parent::setUp();
		Sr::createSqlite3Database('test.sqlite3');
	}

	public function tearDown() {
		parent::tearDown();
		@unlink('test.sqlite3');
	}

	public function init() {
		Sr::createSqlite3Database('test.sqlite3');
		$config = array(
		    'driverType' => 'sqlite',
		    'debug' => true,
		    'pconnect' => true,
		    'tablePrefix' => 'test_',
		    'tablePrefixSqlIdentifier' => '_tablePrefix_',
		    //是否开启慢查询记录
		    'slowQueryDebug' => FALSE,
		    'slowQueryTime' => 3000, //单位毫秒，1秒=1000毫秒
		    'slowQueryHandle' => new Soter_Database_SlowQuery_Handle_Default(),
		    'masters' => array(
			'master01' => array(
			    'hostname' => 'test.sqlite3', //sqlite3数据库路径
			)
		    ),
		    'slaves' => array(
//		    'slave01' => array(
//		    )
		    )
		);
		$this->db = new Soter_Database_ActiveRecord($config);
		$aSql = 'CREATE TABLE `test_a` (`id` INTEGER  PRIMARY KEY AUTOINCREMENT,`name` varchar(10) NOT NULL,`gid` int(11) NOT NULL)';
		$bSql = 'CREATE TABLE `test_b` (`id` INTEGER  PRIMARY KEY AUTOINCREMENT,`gname` varchar(10) NOT NULL,`cid` int(11) NOT NULL)';
		$cSql = 'CREATE TABLE `test_c` (`id` INTEGER  PRIMARY KEY AUTOINCREMENT,`cname` varchar(10) NOT NULL)';

		$this->clean();
		$this->assertTrue($this->db->execute($aSql));
		$this->assertTrue($this->db->execute($bSql));
		$this->assertTrue($this->db->execute($cSql));
	}

	public function testCreate() {
		$this->init();
		$this->db->insert('a', array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)));
		$this->assertEqual($this->db->execute(), 1);
		$this->clean();
	}

	public function testCreateBatch() {
		$this->init();
		$data[] = array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$data[] = array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$data[] = array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$this->db->insertBatch('a', $data);
		$this->assertEqual($this->db->execute(), 3);
		$this->clean();
	}


	public function testDelete() {
		$this->init();
		$this->db->insert('a', array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)))->execute();
		$this->assertEqual($this->db->delete('a')->execute(), 1);
		$this->clean();
	}

	public function testUpdate() {
		$this->init();
		$this->db->insert('a', array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)))->execute();
		$this->assertEqual($this->db->update('a', array('name' => '2222'), array('id' => $this->db->lastId()))->execute(), 1);
		$this->clean();
	}

	public function testUpdateBatch() {
		$this->init();
		$data[] = array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$data[] = array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$data[] = array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$firstId = 0;
		foreach ($data as $key => $value) {
			$this->assertEqual($this->db->insert('a', $value)->execute(), 1);
			if (!$firstId) {
				$firstId = $this->db->lastId();
			}
		}
		$updata[] = array('id' => $firstId, 'name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$updata[] = array('id' => ++$firstId, 'name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$updata[] = array('id' => ++$firstId, 'name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$this->db->updateBatch('a', $updata, 'id');
		$this->assertEqual($this->db->execute(), 3);
		$this->clean();
	}

	public function testSelect() {
		$this->init();
		$datac[] = array('cname' => 'cname' . rand(1000, 10000));
		$datac[] = array('cname' => 'cname' . rand(1000, 10000));
		$datac[] = array('cname' => 'cname' . rand(1000, 10000));
		$this->db->insertBatch('c', $datac);
		$this->assertEqual($this->db->execute(), 3);
		$firstId = $this->db->lastId();
		$datab[] = array('gname' => 'gname' . rand(1000, 10000), 'cid' => $firstId);
		$datab[] = array('gname' => 'gname' . rand(1000, 10000), 'cid' => ++$firstId);
		$datab[] = array('gname' => 'gname' . rand(1000, 10000), 'cid' => ++$firstId);
		$this->db->insertBatch('b', $datab);
		$this->assertEqual($this->db->execute(), 3);
		$firstId = $this->db->lastId();
		$data[] = array('name' => 'name' . rand(1000, 10000), 'gid' => $firstId);
		$data[] = array('name' => 'name' . rand(1000, 10000), 'gid' => ++$firstId);
		$data[] = array('name' => 'name' . rand(1000, 10000), 'gid' => ++$firstId);
		$this->db->insertBatch('a', $data);
		$this->assertEqual($this->db->execute(), 3);

		$this->assertEqual($this->db->select('cname')->from('c')->execute()->total(), 3);
		$this->assertEqual($this->db->select('cname')->from('c')
				->where(array('id >' => 1))
				->execute()->total(), 2);
		$this->assertEqual($this->db->select('cname')->from('c')
				->where(array('id =' => 1, 'id <' => 2))
				->execute()->total(), 1);
		$this->assertEqual($this->db->select('cname')->from('c')
				->where(array('id <' => 2))
				->execute()->total(), 1);
		$this->assertEqual($this->db->select('cname')->from('c')
				->where(array('id <=' => 2))
				->execute()->total(), 2);
		$this->assertEqual($this->db
				->select('c.id as cat_id , b.id as group_id, dd.id as user_id')
				->from('a', 'dd')
				->join('b', 'dd.gid=b.id', 'left')
				->join('c', 'b.cid=c.id', 'left')
				->where(array('dd.id <=' => 2))
				->execute()->total(), 2);

		$this->assertEqual($this->db->select('cname')->from('c')
				->where(array('id <=' => 2, 'id !=' => 3), '(', ')')
				->where(array('id >=' => 0))
				->execute()->total(), 2);

		$this->assertEqual($this->db->select('cname')->from('c')
				->limit(0, 1)
				->execute()->total(), 1);
		$rs = $this->db->select('id')->from('c')
			->groupBy('id')
			->orderBy('id', 'desc')
			->limit(0, 2)
			->execute();
		$this->assertEqual($rs->total(), 2);
		$this->assertEqual($rs->key('id'), 3);
		$this->assertEqual(count($rs->keys('id')), 2);
		$this->clean();
	}

	public function testTransactions() {
		$this->init();
		try {
			$this->db->begin();
			$datac = array('cname' => 'cname' . rand(1000, 10000));
			$this->assertEqual($this->db->insert('c', $datac)->execute(), 1);
			//触发错误
			$this->db->select('none')->from('a')->execute();
			$this->db->commit();
		} catch (Exception $exc) {
			$this->db->rollback();
			$this->assertEqual($this->db->from('c')->execute()->total(), 0);
		}

		$this->init();
		try {
			$this->db->begin();
			$datac = array('cname' => 'cname' . rand(1000, 10000));
			$this->assertEqual($this->db->insert('c', $datac)->execute(), 1);
			$this->db->select('*')->from('a')->execute();
			$this->db->commit();
			$this->assertEqual($this->db->from('c')->execute()->total(), 1);
		} catch (Exception $exc) {
			$this->db->rollback();
			//不应该会到这里
			$this->assertTrue(false);
		}
		$this->clean();
	}

	public function clean() {
		$this->assertTrue($this->db->execute('DROP TABLE if exists  test_a'));
		$this->assertTrue($this->db->execute('DROP TABLE if exists  test_b'));
		$this->assertTrue($this->db->execute('DROP TABLE if exists  test_c'));
	}

}
