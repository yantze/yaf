<?php
/**
 * Yaf 入口文件
 * 
 * @author  Laruence
 * @date    2011-05-13 15:00
 * @version $Id$ 
 */

if (phpversion() >= "5.3") {
	define("APPLICATION_PATH", __DIR__);
} else {
	define("APPLICATION_PATH", dirname(__FILE__));
}
if (!extension_loaded('yaf')) {
    //we should load the framework from classes
    include(APPLICATION_PATH . '/../globals/framework/loader.php');
}
error_reporting(E_ALL);
$app = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini", 'production');
$app->bootstrap()->run();
