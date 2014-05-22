<?php
//启用composer task manager
require "globals/composer/vendor/autoload.php";


session_start();

define('APPLICATION_PATH', dirname(__FILE__));

$application = new Yaf_Application( APPLICATION_PATH . "/conf/application.ini");

$application->bootstrap()->run();
?>
