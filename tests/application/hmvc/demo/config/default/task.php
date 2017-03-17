<?php

return array(
        'php_bin' => '/usr/bin/php', //php命令路径
        'enable' => true, //是否启用(总开关)
        'tasks' => array(
                array(
                        'class' => 'TestTask', //task类名称不需要Task_前缀,还可以是task文件路径,比如:User/Score,就是类Task_User_Score.
                        'enable' => true, //是否启用task
                        'args' => ' --debug', //额外的传递给task的命令行参数
                        'pidfile' => '', //pid文件路径,留空会使用默认规则在storage下面生成pid文件
                        'cron' => ' */1 * * * * *', //执行周期,六位写法,第一位是秒,后五位是标准的crontab五位写法
                        'log' => true, //是否记录日志
                        'log_path' => '/tmp/test.log', //日志文件路径
                        'log_size' => 2 * 1024 * 1024, //日志最大大小,单位字节
                ),
        )
);
