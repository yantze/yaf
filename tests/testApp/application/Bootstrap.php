<?php
/**
 * Bootstrap类, 在这个类中, 所以以_init开头的方法
 * 都会被调用, 调用次序和申明次序相同
 * 
 * @author  Laruence
 * @date    2011-05-13 15:24
 * @version $Id$ 
*/

class Bootstrap extends Yaf_Bootstrap_Abstract {
	/**
	 * 把配置存到注册表
	 */
	function _initConfig(Yaf_Dispatcher $dispatcher) {
		$config = Yaf_Application::app()->getConfig();

		Yaf_Registry::set("config",  $config);
	}

	/**
	 * 注册一个插件
	 */
	function _initPlugin($dispatcher) {
		$user = new UserPlugin();
		$dispatcher->registerPlugin($user);
	}
}
