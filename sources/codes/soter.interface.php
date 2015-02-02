<?php

interface Soter_Logger_Writer {

    /**
     * 这里不应该有输出，应该仅记录错误信息到日志系统（文件、数据库等等）<br/>
     * 而且不能执行退出的操作比如exit，die
     * @param Soter_Exception $exception
     */
    public function write(Soter_Exception $exception);
}

interface Soter_Exception_Handle {

    public function handle(Soter_Exception $exception);
}
