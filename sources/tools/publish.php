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
date_default_timezone_set('PRC');
$ver = "v1.0.4";
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
define('SRC_DIR', dirname(__FILE__) . '/../codes/');
define('DIST_DIR', dirname(__FILE__) . '/../../');
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
file_put_contents(DIST_DIR . 'soter.min.php', "<?php\n" . $header . substr(compress_php_src(DIST_DIR . 'soter.php', true), 5));

$index = file_get_contents(SRC_DIR . 'index.php');
$index = str_replace('##{copyright}##', $header, $index);
$index = str_replace("require dirname(__FILE__) . '/' . (isset(\$_GET['release']) ? '../../soter.php' : 'soter.php')", "require dirname(__FILE__) . '/soter.php'", $index);
$index = str_replace("../../tests/", '', $index);
$index = str_replace("->bootstrap()", '', $index);
$index = str_replace("//加载项目自定义bootstrap.php配置,这一句一定要在最后，确保能覆盖上面的配置", '', $index);
file_put_contents(DIST_DIR . 'index.php', $index);

exec('rm -rf ' . DIST_DIR . 'docs/*');
exec('cp -r ' . DIST_DIR . '../soter-docs/* ' . DIST_DIR . 'docs/');

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

function compress_php_src($src, $is_file = false) {
	// Whitespaces left and right from this signs can be ignored
	static $IW = array(
	    T_CONCAT_EQUAL, // .=
	    T_DOUBLE_ARROW, // =>
	    T_BOOLEAN_AND, // &&
	    T_BOOLEAN_OR, // ||
	    T_IS_EQUAL, // ==
	    T_IS_NOT_EQUAL, // != or <>
	    T_IS_SMALLER_OR_EQUAL, // <=
	    T_IS_GREATER_OR_EQUAL, // >=
	    T_INC, // ++
	    T_DEC, // --
	    T_PLUS_EQUAL, // +=
	    T_MINUS_EQUAL, // -=
	    T_MUL_EQUAL, // *=
	    T_DIV_EQUAL, // /=
	    T_IS_IDENTICAL, // ===
	    T_IS_NOT_IDENTICAL, // !==
	    T_DOUBLE_COLON, // ::
	    T_PAAMAYIM_NEKUDOTAYIM, // ::
	    T_OBJECT_OPERATOR, // ->
	    T_DOLLAR_OPEN_CURLY_BRACES, // ${
	    T_AND_EQUAL, // &=
	    T_MOD_EQUAL, // %=
	    T_XOR_EQUAL, // ^=
	    T_OR_EQUAL, // |=
	    T_SL, // <<
	    T_SR, // >>
	    T_SL_EQUAL, // <<=
	    T_SR_EQUAL, // >>=
	);
	if ($is_file) {
		if (!$src = file_get_contents($src)) {
			return false;
		}
	}
	$tokens = token_get_all($src);

	$new = "";
	$c = sizeof($tokens);
	$iw = false; // ignore whitespace
	$ih = false; // in HEREDOC
	$ls = "";    // last sign
	$ot = null;  // open tag
	for ($i = 0; $i < $c; $i++) {
		$token = $tokens[$i];
		if (is_array($token)) {
			list($tn, $ts) = $token; // tokens: number, string, line
			$tname = token_name($tn);
			if ($tn == T_INLINE_HTML) {
				$new .= $ts;
				$iw = false;
			} else {
				if ($tn == T_OPEN_TAG) {
					if (strpos($ts, " ") || strpos($ts, "\n") || strpos($ts, "\t") || strpos($ts, "\r")) {
						$ts = rtrim($ts);
					}
					$ts .= " ";
					$new .= $ts;
					$ot = T_OPEN_TAG;
					$iw = true;
				} elseif ($tn == T_OPEN_TAG_WITH_ECHO) {
					$new .= $ts;
					$ot = T_OPEN_TAG_WITH_ECHO;
					$iw = true;
				} elseif ($tn == T_CLOSE_TAG) {
					if ($ot == T_OPEN_TAG_WITH_ECHO) {
						$new = rtrim($new, "; ");
					} else {
						$ts = " " . $ts;
					}
					$new .= $ts;
					$ot = null;
					$iw = false;
				} elseif (in_array($tn, $IW)) {
					$new .= $ts;
					$iw = true;
				} elseif ($tn == T_CONSTANT_ENCAPSED_STRING || $tn == T_ENCAPSED_AND_WHITESPACE) {
					if ($ts[0] == '"') {
						$ts = addcslashes($ts, "\n\t\r");
					}
					$new .= $ts;
					$iw = true;
				} elseif ($tn == T_WHITESPACE) {
					$nt = @$tokens[$i + 1];
					if (!$iw && (!is_string($nt) || $nt == '$') && !in_array($nt[0], $IW)) {
						$new .= " ";
					}
					$iw = false;
				} elseif ($tn == T_START_HEREDOC) {
					$new .= "<<<S\n";
					$iw = false;
					$ih = true; // in HEREDOC
				} elseif ($tn == T_END_HEREDOC) {
					$new .= "S;";
					$iw = true;
					$ih = false; // in HEREDOC
					for ($j = $i + 1; $j < $c; $j++) {
						if (is_string($tokens[$j]) && $tokens[$j] == ";") {
							$i = $j;
							break;
						} else if ($tokens[$j][0] == T_CLOSE_TAG) {
							break;
						}
					}
				} elseif ($tn == T_COMMENT || $tn == T_DOC_COMMENT) {
					$iw = true;
				} else {
					if (!$ih) {
						$ts = strtolower($ts);
					}
					$new .= $ts;
					$iw = false;
				}
			}
			$ls = "";
		} else {
			if (($token != ";" && $token != ":") || $ls != $token) {
				$new .= $token;
				$ls = $token;
			}
			$iw = true;
		}
	}
	return $new;
}
