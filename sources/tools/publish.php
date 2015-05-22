<?php

define('SOTER_RUN_MODE_PLUGIN', TRUE);
require dirname(__FILE__) . '/../codes/index.php';
/*
  //
  //                       _oo0oo_
  //                      o8888888o
  //                      88" . "88
  //                      (| -_- |)
  //                      0\  =  /0
  //                    ___/`---'\___
  //                  .' \\|     |// '.
  //                 / \\|||  :  |||// \
  //                / _||||| -:- |||||- \
  //               |   | \\\  -  /// |   |
  //               | \_|  ''\---/''  |_/ |
  //               \  .-\__  '-'  ___/-. /
  //             ___'. .'  /--.--\  `. .'___
  //          ."" '<  `.___\_<|>_/___.' >' "".
  //         | | :  `- \`.;`\ _ /`;.`/ - ` : | |
  //         \  \ `_.   \_ __\ /__ _/   .-` /  /
  //     =====`-.____`.___ \_____/___.-`___.-'=====
  //                       `=---='
  //
  //
  //     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
  //

  佛祖保佑         永无BUG
 */
define('SRC_DIR', dirname(__FILE__) . '/../codes/');
define('DIST_DIR', dirname(__FILE__) . '/../../');
date_default_timezone_set('PRC');
$ver = "v1.0.36";

if (Sr::getOpt('version')) {
	$ver = Sr::getOpt('version');
}
if (Sr::getOpt('docs')) {
	$contents = file_get_contents($docIndex = DIST_DIR . '../soter-docs/index.html');
	$contents = preg_replace('/v\d+.\d+.\d+/', $ver, $contents);
	file_put_contents($docIndex, $contents);
}

$header = '/*
 * Copyright ' . date('Y') . ' Soter(狂奔的蜗牛 672308444@163.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Soter
 *
 * An open source application development framework for PHP 5.3.0 or newer
 *
 * @package       Soter
 * @author        狂奔的蜗牛
 * @email         672308444@163.com
 * @copyright     Copyright (c) 2015 - ' . date('Y') . ', 狂奔的蜗牛, Inc.
 * @link          http://git.oschina.net/snail/soter
 * @since         ' . $ver . '
 * @createdtime   ' . date('Y-m-d H:i:s') . '
 */
 ';

$files = array(
    'soter.class.php',
    'SoterPDO.php',
    'soter.interface.php',
    'soter.abstract.php',
    'soter.exception.php',
    'soter.tools.classes.php',
);
$soter = "<?php\n" . $header . "\n";
foreach ($files as $file) {
	$content = file_get_contents(SRC_DIR . $file);
	common_replace($content);
	$soter.=$content;
}
file_put_contents(DIST_DIR . 'soter.php', $soter);
file_put_contents(DIST_DIR . 'soter.min.php', "<?php\n" . $header . substr(php_strip_whitespace(DIST_DIR . 'soter.php'), 5));

$index = file_get_contents(SRC_DIR . 'index.php');
$index = str_replace('##{copyright}##', $header, $index);
$index = str_replace("require dirname(__FILE__) . '/' . (isset(\$_GET['release']) ? '../../soter.php' : 'soter.php')", "require dirname(__FILE__) . '/soter.php'", $index);
$index = str_replace("../../tests/", '', $index);
$index = str_replace("->bootstrap()", '', $index);
$index = str_replace("//加载项目自定义bootstrap.php配置,这一句一定要在最后，确保能覆盖上面的配置", '', $index);
file_put_contents(DIST_DIR . 'index.php', $index);

exec('rm -rf ' . DIST_DIR . 'docs/*');
exec('cp -r ' . DIST_DIR . '../soter-docs/* ' . DIST_DIR . 'docs/');

//内测版生成
exec('cd ' . DIST_DIR . '&&tar zcvf "' . '../soter-alpha-' . $ver . '.tar.gz' . '" application docs composer.json index.php LICENSE README.md soter.min.php soter.php');

function common_replace(&$str) {
	$str = preg_replace('|^ *// *[\w].*$\n|m', '', $str); //去掉英文单行注释
	$str = preg_replace('|^ *$\n|m', '', $str); //去掉空行
	$str = preg_replace('| +$|m', '', $str); //去掉行尾空格
	$str = preg_replace_callback('|^ +|m', "space2tab", $str); //行首空格缩进转为制表符缩进
	$str = substr($str, 5);
}

//行首空格缩进转为制表符缩进
function space2tab($arr) {
	$tab_count = 4;
	$space = $arr[0];
	$len = strlen($space);
	$left = $len % $tab_count;
	$c = floor($len / $tab_count);
	$str = '';
	for ($i = 0; $i < $c; $i++) {
		$str.="\t";
	}
	for ($i = 0; $i < $left; $i++) {
		$str.=" ";
	}
	return $str;
}