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
		//@unlink('test.sqlite3');
	}

	public function init() {
		$config = array(
		    'group' => 'sqlite',
		    'driverType' => 'sqlite',
		    'debug' => true,
		    'pconnect' => true,
		    'tablePrefix' => 'test_',
		    'tablePrefixSqlIdentifier' => '_tablePrefix_',
		    'database' => Sr::realPath('test.sqlite3'), //sqlite3数据库路径
		    //是否开启慢查询记录
		    'slowQueryDebug' => false,
		    'slowQueryTime' => 3000, //单位毫秒，1秒=1000毫秒
		    'slowQueryHandle' => new Soter_Database_SlowQuery_Handle_Default()
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

	public function clean() {
		$this->assertTrue($this->db->execute('DROP TABLE if exists  test_a'));
		$this->assertTrue($this->db->execute('DROP TABLE if exists  test_b'));
		$this->assertTrue($this->db->execute('DROP TABLE if exists  test_c'));
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
		$this->assertEqual($this->db->lastId(), 1);
		$this->clean();

		$this->init();
		$data2[] = array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$this->db->insertBatch('a', $data2);
		$this->assertEqual($this->db->execute(), 1);
		$this->assertEqual($this->db->lastId(), 1);
		$this->db->insert('a', array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)));
		$this->assertEqual($this->db->execute(), 1);
		$this->assertEqual($this->db->lastId(), 2);
		$this->db->insertBatch('a', $data)->execute();
		$this->assertEqual($this->db->lastId(), 3);
		$this->clean();
	}

	public function testReplace() {
		$this->init();
		$this->db->replace('a', array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)));
		$this->assertEqual($this->db->execute(), 1);
		$this->clean();
	}

	public function testReplaceBatch() {
		$this->init();
		$data[] = array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$data[] = array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$data[] = array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$this->db->replaceBatch('a', $data);
		$this->assertEqual($this->db->execute(), 3);
		$this->assertEqual($this->db->lastId(), 1);
		$this->clean();

		$this->init();
		$data2[] = array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$this->db->replaceBatch('a', $data2);
		$this->assertEqual($this->db->execute(), 1);
		$this->assertEqual($this->db->lastId(), 1);
		$this->db->replace('a', array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)));
		$this->assertEqual($this->db->execute(), 1);
		$this->assertEqual($this->db->lastId(), 2);
		$this->db->replaceBatch('a', $data)->execute();
		$this->assertEqual($this->db->lastId(), 3);
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

	public function testUpdateSet() {
		$this->init();
		$this->db->insert('a', array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)))->execute();
		$this->assertEqual($this->db->where(array('id' => $this->db->lastId()))->set('gid', 'gid + 1', false)->update('a')->execute(), 1);
		$this->clean();
	}

	public function testUpdateBatch() {
		$this->init();
		$data[] = array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$data[] = array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$data[] = array('name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$this->db->insertBatch('a', $data);
		$this->assertEqual($this->db->execute(), 3);
		$firstId = $this->db->lastId();
		$this->assertEqual($firstId, 1);
		$updata[] = array('id' => $firstId, 'name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$updata[] = array('id' => ++$firstId, 'name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$updata[] = array('id' => ++$firstId, 'name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000));
		$this->db->updateBatch('a', $updata, 'id');
		$this->assertEqual($this->db->execute(), 3);
		$this->clean();
	}

	public function testUpdateBatchSet() {
		$this->init();
		$data[] = array('name' => 'name1', 'gid' => 3);
		$data[] = array('name' => 'name2', 'gid' => 2);
		$data[] = array('name' => 'name3', 'gid' => 1);
		$this->db->insertBatch('a', $data);
		$this->assertEqual($this->db->execute(), 3);
		$firstId = $this->db->lastId();
		$this->assertEqual($firstId, 1);
		$updata[] = array('id' => $firstId, 'gid' => array('gid +' => 1));
		$updata[] = array('id' => ++$firstId, 'gid' => array('gid +' => 3));
		$updata[] = array('id' => ++$firstId, 'gid' => array('gid +' => 5));
		$this->db->updateBatch('a', $updata, 'id');
		$this->assertEqual($this->db->execute(), 3);
		$rows = $this->db->from('a')->execute()->rows();
		$this->assertEqual($rows[0]['gid'], 4);
		$this->assertEqual($rows[1]['gid'], 5);
		$this->assertEqual($rows[2]['gid'], 6);
		$this->clean();
	}

	public function testSelect() {
		$this->init();
		$datac[] = array('cname' => 'cname1');
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
				->where(array('id <=' => 2, 'id <>' => 3), '(', ')')
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
		$this->assertEqual($rs->value('id'), 3);
		$this->assertEqual(count($rs->values('id')), 2);

		$this->db->insert('c', array('cname' => 'cname1'))->execute();
		$rs = $this->db->select('count(' . $this->db->wrap('id') . ') as total,id')->from('c')
			->groupBy('cname')
			->having('total >= 1')
			->orderBy('total', 'desc')
			->execute();
		$this->assertEqual($rs->total(), 3);
		$this->assertEqual($rs->value('total'), 2);
		$this->assertEqual(count($rs->values('total')), 3);

		$this->clean();
	}

	public function testBean() {
		$this->init();
		$firstId = 1;
		$data[] = array('name' => 'name' . rand(1000, 10000), 'gid' => $firstId);
		$data[] = array('name' => 'name' . rand(1000, 10000), 'gid' => ++$firstId);
		$data[] = array('name' => 'name' . rand(1000, 10000), 'gid' => ++$firstId);
		$this->db->insertBatch('a', $data);
		$this->assertEqual($this->db->execute(), 3);
		$object = $this->db->from('a')->where(array('id' => 1))->execute()->object('TestA');
		$this->assertIsA($object, 'Soter_Bean');
		$this->assertEqual($object->getId(), 1);
		$object = $this->db->from('a')->where(array('id >' => 0))->execute()->object('Bean_TestA', 2);
		$this->assertEqual($object->getId(), 3);
		$this->assertIsA($object, 'Soter_Bean');
		$objects = $this->db->from('a')->execute()->objects('TestA');
		foreach ($objects as $object) {
			$this->assertEqual($object->getId(), $object->getGid());
			$this->assertIsA($object, 'Soter_Bean');
		}
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

	public function testKey() {
		$this->init();
		$this->db->insert('a', array('id' => 5, 'name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)));
		$this->assertEqual($this->db->execute(), 1);
		$rows = $this->db->from('a')->execute()->key('id')->rows();
		$key = key($rows);
		$this->assertEqual($key, 5);
		$this->clean();
	}

	public function testLock() {
		$this->init();
		$this->db->lock();
		$this->db->insert('a', array('id' => 5, 'name' => 'name' . rand(1000, 10000), 'gid' => rand(1000, 10000)));
		$this->assertEqual($this->db->execute(), 1);
		$db1 = $this->db->getLastPdoInstance();
		$rows = $this->db->from('a')->execute()->key('id')->rows();
		$db2 = $this->db->getLastPdoInstance();
		$this->assertReference($db2, $db1);
		$key = key($rows);
		$this->assertEqual($key, 5);
		$this->assertEqual($this->db->isLocked(), true);
		$this->db->unlock();
		$this->clean();
	}

}
