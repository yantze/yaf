<?php
date_default_timezone_set('Asia/Chongqing');

//启用composer task manager
require "globals/composer/vendor/autoload.php";

define('APPLICATION_PATH', dirname(__FILE__));
define('APP_PATH', dirname(__FILE__));

if(!extension_loaded("yaf")){
   include(APPLICATION_PATH.'/globals/framework/loader.php');
}
$application = new Yaf_Application( APPLICATION_PATH. "/conf/application.ini");

$application->bootstrap()->run();
?>
