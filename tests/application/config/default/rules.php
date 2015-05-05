<?php

//验证规则返回布尔代表成功和失败
//如果要改变传入的值，把改变后的值赋值给$returnValue变量即可
//对于一个字段多条验证规则存在的时候，如果设置$break为true那么该字段的该条规则之后的验证规则不在起作用

return array(
    'default' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
	    if (empty($value)) {
		    $returnValue = $args[0];
	    }
	    return true;
    },
    'optional' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
	    $break = !isset($data[$key]);
	    return true;
    },
    'required' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
	    return !empty($value);
    },
    'functions' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
	    $returnValue = $value;
	    foreach ($args as $function) {
		    $returnValue = $function($returnValue);
	    }
	    return true;
    },
);
