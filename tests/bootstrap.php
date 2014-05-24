<?php
if (defined('TEST_APPLICATION_PATH')) {
    error_reporting(E_ALL ^ E_NOTICE);
} else {
   error_reporting(E_ALL | E_STRICT);  
}
if (!defined('APPLICATION_PATH')) {
    if (phpversion() >= "5.3") {
        define("APPLICATION_PATH", __DIR__.'/..');
    } else {
        define("APPLICATION_PATH", dirname(__FILE__).'/..');
    }
    if (!extension_loaded('yaf')) {
        //dl('yaf.so');
        //we should load the framework from classes
        include(APPLICATION_PATH . '/framework/loader.php');
    }
}
define('TEST_APPLICATION_PATH', realpath(APPLICATION_PATH).'/tests/testApp/');