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

//src
chdir($rootDir);
shell_exec('git add .');
shell_exec('git commit -a -m release'.$ver);
shell_exec('git checkout master');
shell_exec('git merge dev');
shell_exec("rm -rf  $rootDir/tests $rootDir/sources");
shell_exec('git add .');
shell_exec("git commit -a  -m release$ver");
shell_exec("git tag -d $ver");
shell_exec("git tag -a $ver -m $ver");
shell_exec("git push origin :refs/tags/$ver");
shell_exec("git push origin dev");
shell_exec("git push origin master");
shell_exec("git push origin --tags");
shell_exec('git checkout dev');

//docs
echo "$rootDir/../soter-docs\n";
chdir("$rootDir/../soter-docs");
shell_exec("git add . >/dev/null");
shell_exec("git commit -a -m release$ver >/dev/null");
shell_exec("git tag -d $ver >/dev/null");
shell_exec("git tag -a $ver -m $ver >/dev/null");
shell_exec("git push origin :refs/tags/$ver");
shell_exec("git push origin master >/dev/null");
shell_exec("git push origin --tags >/dev/nul");

//src
chdir($rootDir);
echo shell_exec('git status');
echo "\n======================\n!!! delete backup($tmp)?[y/N]:";
$confirm = trim(fgets(STDIN),"\n");
if(strtolower($confirm)=='y'){
	shell_exec("rm -rf  $tmp");
	echo "$tmp deleted\n";
}
echo "done\n";