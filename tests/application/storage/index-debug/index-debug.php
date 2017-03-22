<?php defined("IN_SOTER") or exit();?>

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:48:28

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:48:28

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:48:28

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:48:29

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:48:29

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:48:29

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 3 ms
Time : 2016-06-14 16:48:30

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:48:30

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:48:30

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-06-14 16:48:31

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-06-14 16:48:31

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:48:31

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 4 ms
Time : 2016-06-14 16:48:32

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:48:32

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:57:23

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:57:23

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-06-14 16:57:23

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 0 ms
Time : 2016-06-14 16:57:23

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:57:24

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:57:24

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:57:25

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:57:25

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-06-14 16:57:25

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:57:26

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:57:26

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-06-14 16:57:26

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:57:27

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 1 ms
Time : 2016-06-14 16:57:27

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-09-08 15:46:56

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:46:57

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-09-08 15:46:57

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 2 ms
Time : 2016-09-08 15:46:57

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 2 ms
Time : 2016-09-08 15:46:57

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-09-08 15:46:58

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:46:58

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 2 ms
Time : 2016-09-08 15:46:59

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-09-08 15:46:59

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 2 ms
Time : 2016-09-08 15:47:00

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:47:00

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 31 ms
Time : 2016-09-08 15:47:00

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 2 ms
Time : 2016-09-08 15:47:00

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 2 ms
Time : 2016-09-08 15:47:01

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:48:56

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:48:56

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 15 ms
Time : 2016-09-08 15:48:56

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:48:56

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:48:56

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-09-08 15:48:57

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:48:57

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:48:57

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:48:58

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-09-08 15:48:58

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-09-08 15:48:58

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:48:59

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:48:59

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:48:59

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-09-08 15:50:01

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-09-08 15:50:01

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 2 ms
Time : 2016-09-08 15:50:01

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 1 ms
Time : 2016-09-08 15:50:01

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 2 ms
Time : 2016-09-08 15:50:02

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-09-08 15:50:02

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 2 ms
Time : 2016-09-08 15:50:02

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:50:02

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 2 ms
Time : 2016-09-08 15:50:03

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 2 ms
Time : 2016-09-08 15:50:03

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:50:03

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-09-08 15:50:04

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:50:04

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 2 ms
Time : 2016-09-08 15:50:05

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:51:01

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:51:01

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:51:01

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:51:01

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:51:02

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:51:02

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-09-08 15:51:03

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:51:03

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:51:03

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 2 ms
Time : 2016-09-08 15:51:04

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-09-08 15:51:04

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:51:04

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:51:04

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:51:05

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 13 ms
Time : 2016-09-08 15:56:43

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:56:44

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-09-08 15:56:44

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:56:44

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 2 ms
Time : 2016-09-08 15:56:44

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-09-08 15:56:45

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 3 ms
Time : 2016-09-08 15:56:45

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-09-08 15:56:45

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 2 ms
Time : 2016-09-08 15:56:45

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-09-08 15:56:46

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 2 ms
Time : 2016-09-08 15:56:46

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-09-08 15:56:46

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:56:47

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2016-09-08 15:56:48

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:33:26

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:33:28

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:33:28

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 68 ms
Time : 2016-10-14 14:33:28

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:33:29

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:33:30

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:33:31

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:33:31

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:33:32

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:33:33

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:33:33

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:33:34

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:33:35

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:33:36

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:35:52

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:35:53

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:35:53

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:35:53

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:35:54

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:35:55

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:35:55

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:35:55

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:35:58

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 2 ms
Time : 2016-10-14 14:35:58

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:35:58

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:35:59

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 2 ms
Time : 2016-10-14 14:36:00

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:36:01

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:36:48

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:36:49

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:36:49

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:36:49

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:36:50

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:36:51

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:36:52

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:36:52

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:36:53

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:36:54

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:36:54

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:36:54

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:36:55

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:36:56

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:38:20

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:38:21

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:38:21

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:38:22

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:38:23

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:38:24

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:38:24

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:38:24

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:38:26

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:38:26

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 2 ms
Time : 2016-10-14 14:38:26

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:38:27

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:38:28

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:38:29

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:40:02

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:40:03

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:40:03

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:40:03

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:40:04

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:40:05

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:40:06

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:40:06

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:40:07

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:40:07

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:40:07

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:40:08

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:40:09

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:40:10

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:46:28

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:46:29

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:46:29

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:46:29

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:46:31

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:46:32

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:46:32

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:46:32

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:46:33

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:46:34

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:46:34

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:46:35

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2016-10-14 14:46:36

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2016-10-14 14:46:36

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-01-03 17:34:58

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:34:59

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:34:59

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:34:59

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:34:59

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:35:00

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-01-03 17:35:01

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:35:01

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-01-03 17:35:01

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:35:02

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:35:02

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:35:03

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:35:03

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 1 ms
Time : 2017-01-03 17:35:04

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:43:48

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:43:48

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-01-03 17:43:48

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 1 ms
Time : 2017-01-03 17:43:48

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:43:49

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:43:50

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:43:50

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:43:50

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-01-03 17:43:51

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-01-03 17:43:52

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:43:52

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:43:52

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:43:53

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 17:43:54

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-01-03 18:37:07

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-01-03 18:37:08

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 18:37:08

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 1 ms
Time : 2017-01-03 18:37:08

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-01-03 18:37:09

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 18:37:10

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 18:37:10

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 18:37:10

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 18:37:11

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 18:37:12

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 18:37:12

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 18:37:13

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-01-03 18:37:14

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2017-01-03 18:37:16

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-17 13:56:51

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-17 13:56:52

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-17 13:56:52

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 1 ms
Time : 2017-03-17 13:56:52

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-17 13:56:52

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-17 13:56:53

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-17 13:56:54

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-17 13:56:54

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-17 13:56:54

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-17 13:56:55

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-17 13:56:55

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-17 13:56:56

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-17 13:56:56

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2017-03-17 13:56:57

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 14:24:16

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 14:24:16

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 14:24:16

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 14:24:16

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 14:24:17

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 14:24:18

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 14:24:19

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 14:24:19

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 14:24:19

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 14:24:20

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 14:24:20

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 14:24:21

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 14:24:22

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 14:24:23

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:13:27

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:13:27

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:13:27

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:13:27

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:13:28

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:13:29

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:13:30

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:13:30

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:13:31

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:13:32

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:13:32

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:13:33

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:13:34

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:13:35

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:41:04

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:41:05

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:41:05

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:41:05

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:41:07

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:41:08

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:41:09

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:41:09

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:41:10

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:41:11

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:41:11

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:41:12

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:41:13

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:41:14

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:41:34

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:41:35

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:41:35

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:41:35

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:41:36

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:41:37

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:41:38

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:41:38

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:41:40

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:41:41

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:41:41

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:41:41

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:41:42

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:41:43

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:42:36

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:42:38

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:42:38

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:42:38

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:42:39

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:42:40

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:42:41

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:42:41

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:42:42

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:42:43

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:42:43

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:42:44

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 15:42:45

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 15:42:46

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:14:00

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:14:01

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:14:01

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:14:01

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:14:02

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:14:03

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:14:04

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:14:04

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:14:05

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:14:06

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:14:06

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:14:07

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:14:09

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:14:09

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:15:06

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:15:06

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:15:06

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:15:06

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:15:08

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:15:09

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:15:10

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:15:10

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:15:10

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:15:11

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:15:11

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:15:12

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:15:13

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:15:14

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:08

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:10

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:10

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:10

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:11

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:12

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:13

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:13

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:14

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:15

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:15

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:16:16

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:17

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:18

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:40

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:41

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:41

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:41

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:42

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:43

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:44

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:44

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:45

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:46

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:46

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:47

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:48

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:16:49

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:19:43

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:19:44

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:19:44

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:19:44

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:19:45

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:19:46

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:19:46

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:19:46

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:19:48

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:19:49

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:19:49

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:19:50

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:19:51

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:19:52

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:32:36

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:32:37

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:32:37

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:32:37

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:32:38

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:32:39

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:32:40

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:32:40

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:32:41

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:32:42

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:32:42

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:32:43

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:32:44

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:32:45

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:33:23

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:33:23

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:33:23

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:33:23

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:33:24

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:33:25

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:33:26

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:33:26

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:33:27

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 16:33:29

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:33:29

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:33:29

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:33:31

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 16:33:32

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 18:31:50

SQL : 
 SELECT `cname`
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 18:31:51

SQL : 
 SELECT `cname`
 FROM  `test_c` 
 LIMIT 0 , 1
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 18:31:51

SQL : 
 SELECT count(`id`) as total,`id`
 FROM  `test_c` 
 GROUP BY `cname`
 HAVING `total` >= ?
 ORDER BY `total` DESC
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '4',
    'Extra' => 'Using temporary; Using filesort',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 18:31:51

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 18:31:52

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 18:31:53

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 18:31:55

SQL : 
 SELECT *
 FROM  `test_c` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_c',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '3',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 18:31:55

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 1 ms
Time : 2017-03-21 18:31:56

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 18:31:56

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 18:31:56

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 18:31:57

SQL : 
 SELECT *
 FROM  `test_a` 
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => '',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 18:31:58

SQL : select * from test_a where name like '%?%'
Explain : array (
  0 => 
  array (
    'id' => '1',
    'select_type' => 'SIMPLE',
    'table' => 'test_a',
    'type' => 'ALL',
    'possible_keys' => NULL,
    'key' => NULL,
    'key_len' => NULL,
    'ref' => NULL,
    'rows' => '1',
    'Extra' => 'Using where',
  ),
)
UsingTime : 0 ms
Time : 2017-03-21 18:31:59
