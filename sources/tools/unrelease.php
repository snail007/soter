<?php
if(empty($argv[1])){
	exit('argument version missing,php '.$argv[0].' <version>');
}
$ver=$argv[1];
$rootDir= dirname(dirname(dirname(__FILE__)));
chdir($rootDir);
echo shell_exec("git tag -d $ver");
echo shell_exec("git push origin :refs/tags/$ver");
echo "done\n";
