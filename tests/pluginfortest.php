<?php
define('SOTER_RUN_MODE_PLUGIN', TRUE);
require dirname(__FILE__).'/../sources/codes/index.php';
function testUrl($route, $index = 'indexfortest.php/') {
    return 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/' . $index . $route;
}