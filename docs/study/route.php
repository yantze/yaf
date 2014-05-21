<?php
更多：http://cn2.php.net/manual/en/yaf-router.addconfig.php
function _initRoutes(){

    	//添加Yaf_Route_Supervar路由协议
    	Yaf_Dispatcher::getInstance()->getRouter()->addRoute(
            "supervar",new Yaf_Route_Supervar("r")
    	);
    	//添加Yaf_Route_Simple路由协议
    	Yaf_Dispatcher::getInstance()->getRouter()->addRoute(
    	"simple",new Yaf_Route_Simple("m", "c", "a"));
    	
    	/**
    	 * Yaf_Route_Supervar路由协议
    	 * 对于如下请求: "http://domain.com/index.php?r=/a/b/c
    	 * 能得到如下路由结果
    	  
    	 array(
    	 'module'     => 'a',
    	 'controller' => 'b',
    	 'action'     => 'c',
    	 )
    	 */
    	
    	/**
    	 * Yaf_Route_Simple路由协议
    	 * 对于如下请求: "http://domain.com/index.php?m=a&c=b&a=c
    	 * 能得到如下路由结果
    	  
    	 array(
    	 'module'     => 'a',
    	 'controller' => 'b',
    	 'action'     => 'c',
    	 )
    	 */

    	/**
    	 * Yaf_Route_Static[默认路由协议]
    	 * 对于如下请求: "http://domain.com/index.php/a/b/c
    	 * 能得到如下路由结果
    	  
    	 array(
    	 'module'     => 'a',
    	 'controller' => 'b',
    	 'action'     => 'c',
    	 )
    	 */

    	//查询当前使用的所有路由协议
    	$routes = Yaf_Dispatcher::getInstance()->getRouter()->getRoutes();
    	print_r($routes);
    	/**
    	 *print_r打印结果:
    	 Array
    	 (
    	 [_default] => Yaf_Route_Static Object
    	 (
    	 )

    	 [supervar] => Yaf_Route_Supervar Object
    	 (
    	 [_var_name:protected] => r
    	 )

    	 [simple] => Yaf_Route_Simple Object
    	 (
    	 [controller:protected] => c
    	 [module:protected] => m
    	 [action:protected] => a
    	 )
    	 )
    	 */
    
}
