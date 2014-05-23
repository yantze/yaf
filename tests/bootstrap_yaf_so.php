<?php
if (defined('TEST_APPLICATION_PATH')) {
    error_reporting(E_ALL ^ E_NOTICE);
} else {
   error_reporting(E_ALL | E_STRICT);  
}
if (!extension_loaded('yaf')) {
    dl('yaf.so');
    define('YAF_MODE', 'on');
}
if (!defined('APPLICATION_PATH')) {
    if (phpversion() >= "5.3") {
        define("APPLICATION_PATH", __DIR__.'/../');
    } else {
        define("APPLICATION_PATH", dirname(__FILE__).'/../');
    }
}
define('TEST_APPLICATION_PATH', realpath(APPLICATION_PATH).'/tests/testApp/');