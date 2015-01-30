<?php

class Soter {

    private static $soterConfig;

    /**
     * 
     * @return \Soter_Config
     */
    public static function initialize() {
        self::$soterConfig = new Soter_Config();
        Soter_Logger_Writer_Dispatcher::initialize();
        return self::$soterConfig;
    }

    /**
     * 
     * @return Soter_Config
     */
    public static function &getConfig() {
        return self::$soterConfig;
    }

    public static function run() {
        $config=self::getConfig();
        foreach (array_reverse($config->getRouters()) as $router) {
            $route =$router->find($config->getRequest());
            if ($route->found()) {
                $route = $router->route();
                $class = $route->getController();
                $method = $route->getMethod();
                $response = call_user_func_array(array($class, $method),$route->getArgs());
                if ($response instanceof Soter_Response) {
                    $response->output();
                } else {
                    echo $response;
                }
                exit();
            } else {
                $exception = new Soter_Exception_404($route->getController());
                throw $exception;
            }
        }
    }

}

class Sr {

    static function arrayGet($array, $key, $default = null) {
        return isset($array[$key]) ? $array[$key] : $default;
    }

    static function dump() {
        call_user_func_array('var_dump', func_get_args());
    }

}
