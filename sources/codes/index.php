<?php

##{copyright}##
define("IN_SOTER", true);
/* 引入核心 */
require dirname(__FILE__) . '/' . (isset($_GET['release']) ? '../../soter.php' : 'soter.php');
/* 项目目录路径 */
define('SOTER_APP_PATH', Sr::realPath(dirname(__FILE__) . '/../../tests/application') . '/');
/* 项目拓展包路径 */
define('SOTER_PACKAGES_PATH', SOTER_APP_PATH . 'packages/');
/* 初始化系统配置 */
Soter::initialize()
	/* 时区设置 */
	->setTimeZone('PRC')
	/* 项目目录路径 */
	->setApplicationDir(SOTER_APP_PATH)
	/* 注册项目包 */
	->addPackage(SOTER_APP_PATH)
	/* 注册拓展包 */
	->addPackages(array(
		//SOTER_PACKAGES_PATH . 'misc',
	))
	/* 注册自动加载的函数文件 */
	->addAutoloadFunctions(array(
		// 'functions'
	))
	/* 设置运行环境 */
	->setEnvironment(($env = (($cliEnv = Sr::getOpt('env')) ? $cliEnv : Sr::arrayGet($_SERVER, 'ENVIRONMENT'))) ? Sr::config()->getServerEnvironment($env) : Sr::ENV_DEVELOPMENT)
	/* 系统错误显示设置，非产品环境才显示 */
	->setShowError(Sr::config()->getEnvironment() != Sr::ENV_PRODUCTION)

	/**
	 * 下面配置中可以使用：
	 * 1.主项目的claseses目录，主项目类库目录，主项目拓展包里面的类
	 * 2.这几个目录如果存在同名类，使用的优先级高到低是：
	 *   主项目classes->类库classes->拓展包classes->拓展包类库classes
	 */
	/* 入口文件所在目录 */
	->setIndexDir(dirname(__FILE__) . '/')
	/* 入口文件名称 */
	->setIndexName(pathinfo(__FILE__, PATHINFO_BASENAME))
	/* 宕机维护模式 */
	->setIsMaintainMode(false)
	/* 宕机维护模式IP白名单 */
	//->setMaintainIpWhitelist(array('127.0.0.2', '192.168.0.2/32'))
	/* 宕机维护模式处理方法 */
	->setMaintainModeHandle(new Soter_Maintain_Handle_Default())
	/**
	 * 如果服务器是ngix之类代理转发请求到后端apache运行的PHP。
	 * 那么这里应该设置信任的nginx所在服务器的ip。
	 * nginx里面应该设置 X_FORWARDED_FOR server变量来表示真实的客户端IP。
	 * 不然通过Sr::clientIp()是获取不到真实的客户端IP的。
	 * 参数是数组，有多个ip放入数组即可。
	 */
	//->setBackendServerIpWhitelist(array('192.168.2.2'))
	/* 初始化请求 */
	->setRequest(new Soter_Request(Sr::arrayGet($_SERVER, 'PATH_INFO', Sr::arrayGet($_SERVER, 'REDIRECT_PATH_INFO'))))
	/* 网站是否开启了nginx或者apache的url“伪静态”重写，开启了这里设置为true，
	  这样Sr::url方法在生成url的时候就知道是否加上入口文件名称 */
	->setIsRewrite(FALSE)
	/* 注册默认pathinfo路由器 */
	->addRouter(new Soter_Default_Router_PathInfo())
	/* pathinfo路由器,注册uri重写 */
	->setUriRewriter(new Soter_Uri_Rewriter_Default())
	/* 注册默认get路由器 */
	->addRouter(new Soter_Default_Router_Get())
	/* get路由器,url中的控制器的get变量名 */
	->setRouterUrlControllerKey('c')
	/* get路由器,url中的方法的get变量名 */
	->setRouterUrlMethodKey('a')
	/* get路由器,url中的hmvc模块的get变量名 */
	->setRouterUrlModuleKey('m')
	/* 设置自定义的错误显示控制处理类 */
	->setExceptionHandle(new Soter_Exception_Handle_Default())
	/* 错误日志记录，注释掉这行会关闭日志记录，去掉注释则开启日志文件记录 */
	//->addLoggerWriter(new Soter_Logger_FileWriter(SOTER_APP_PATH . 'storage/logs/'))
	/* 设置日志记录子目录格式，参数就是date()函数的第一个参数,默认是 Y-m-d/H */
	->setLogsSubDirNameFormat('Y-m-d/H')
	/* 默认控制器 */
	->setDefaultController('Welcome')
	/* 默认方法 */
	->setDefaultMethod('index')
	/* 控制器方法前缀 */
	->setMethodPrefix('do_')
	/* 方法url后缀 */
	->setMethodUriSubfix('.do')
	/* 注册hmvc模块，数组键是uri里面的hmvc模块名称，值是hmvc模块文件夹名称 */
	->setHmvcModules(array(
		// 'Demo' => 'demo'
	))
	/**
	 * 设置缓存类型
	 * 1.setCacheHandle可以直接传入Soter_Cache缓存类对象
	 * 2.setCacheHandle也可以传入配置文件名称，配置文件里面要返回一个Soter_Cache缓存类对象。
	 */
	->setCacheHandle(new Soter_Cache_File())
	/* 设置session信息 */
	->setSessionConfig(array(
	    'autostart' => false,
	    'cookie_path' => '/',
	    'cookie_domain' => Sr::server('HTTP_HOST'),
	    'session_name' => 'SOTER',
	    'lifetime' => 3600,
	))
	/**
	 * 设置session托管类型
	 * 1.setSessionHandle可以直接传入Soter_Session类对象
	 * 2.setSessionHandle也可以传入配置文件名称，配置文件里面要返回一个Soter_Session类对象。
	 */
	//->setSessionHandle('session')
	/* 设置数据库连接信息，参数可以是配置文件名称；也可以是数据库配置信息数组，即配置文件返回的那个数组。 */
	//->setDatabseConfig('database')
	/* 设置控制器方法缓存规则，参数可以是配置文件名称，也可以是配置规则数组 */
	//->setMethodCacheConfig('method_cache')
	/* 设置自定义数据验证规则，参数可以是配置文件名称，也可以是规则数组 */
	//->setDataCheckRules('rules')
	/* 设置Sr::json()输出处理回调函数，这里可以自定义json输出格式 */
	->setOutputJsonRender(function() {
		$args = func_get_args();
		$code = Sr::arrayGet($args, 0, '');
		$message = Sr::arrayGet($args, 1, '');
		$data = Sr::arrayGet($args, 2, '');
		return @json_encode(array('code' => $code, 'message' => $message, 'data' => $data));
	})
	/* 设置发生异常的时候，调用异常对象的renderJson()方法输出json的回调函数，这里可以自定义json输出格式 */
	->setExceptionJsonRender(function(Exception $e) {
		$json['file'] = $e->getErrorFile();
		$json['line'] = $e->getErrorLine();
		$json['message'] = $e->getErrorMessage();
		$json['type'] = $e->getErrorType();
		$json['code'] = $e->getErrorCode();
		$json['time'] = date('Y/m/d H:i:s T');
		$json['trace'] = $e->getTraceCliString();
		return @json_encode($json);
	})
	//加载项目自定义bootstrap.php配置,这一句一定要在最后，确保能覆盖上面的配置
	->bootstrap()
;

//启动，噪起来
Soter::run();
