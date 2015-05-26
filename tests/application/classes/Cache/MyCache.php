<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MyCache
 *
 * @author pengmeng
 */
class Cache_MyCache implements Soter_Cache {
	
	public function set($key, $value, $cacheTime = 0) {
		echo 'set cache [ ' . $key . ':' . $value . ' ](' . $cacheTime . ' ms)';
		return true;
	}

	public function get($key) {
		echo 'get cache [ ' . $key . ' ]';
		return true;
	}

	public function delete($key) {
		echo 'delete cache [ ' . $key . ' ]';
		return true;
	}

	public function clean() {
		echo 'clean cache';
		return true;
	}

}
