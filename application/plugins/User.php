<?php
 //require "../yaf_classes.php";
/** 
 * 插件类定义
 * UserPlugin.php
 */
class UserPlugin extends Yaf_Plugin_Abstract {
    public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
    }
    public function routerShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
       echo "亲爱的用户 ".Yaf_Session::getInstance()->get("username")." 你好<br>";
    }
}
