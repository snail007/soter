<?php

//验证规则返回布尔代表成功和失败，返回null和其它值代表不设置和设置值

return array(
    'default' => function($key, $value, $data, $args, &$returnValue = null, &$break = false) {
	    if (empty($value)) {
		    $returnValue = $args[0];
	    }
	    return true;
    },
    'optional' => function($key, $value, $data, $args, &$returnValue = null, &$break = false) {
	    $break = true;
	    return true;
    },
    'required' => function($key, $value, $data, $args, &$returnValue = null, &$break = false) {
	    return !empty($value);
    },
    'functions' => function($key, $value, $data, $args, &$returnValue = null, &$break = false) {
	    $returnValue = $value;
	    foreach ($args as $function) {
		    $returnValue = $function($returnValue);
	    }
	    return true;
    },
);
