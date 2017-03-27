<?php
if(empty($argv[1])){
	exit('argument version missing,php '.$argv[0].' <version>');
}
$ver=$argv[1];
$rootDir= dirname(dirname(dirname(__FILE__)));
$tmp= '/tmp/'.uniqid('.soter');
mkdir($tmp);

shell_exec("cp -R  $rootDir/* ".$tmp);
echo "backup location : $tmp\n";
chdir($rootDir);
echo shell_exec('git add .');
echo shell_exec('git commit -a -m release'.$ver);
echo shell_exec('git checkout master');
echo shell_exec("rm -rf  $rootDir/tests $rootDir/sources");
echo shell_exec('git add .');
echo shell_exec("git commit -A .  -m release$ver");
echo shell_exec("git tag -d $ver");
echo shell_exec("git tag -a $ver -m $ver");
echo shell_exec("git push origin :refs/tags/$ver");
echo shell_exec("git push origin --tags");
echo shell_exec('git checkout dev');
echo "delete backup?[y/N] ";
$confirm = fgets(STDIN);
if(strtolower($confirm)=='y'){
	echo shell_exec("rm -rf  $tmp");
}
echo "done\n";