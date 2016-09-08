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
