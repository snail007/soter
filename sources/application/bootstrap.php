<?php

defined('IN_SOTER') or die();

$config = Soter::getConfig();
//配置 项目目录
$config->setApplicationPath(SOTER_APP_DIR);
//配置时区
$config->setTimeZone('PRC');
