<?php
function error_handlerTestCase020($errno, $errstr, $errfile, $errline) {
	$GLOBALS['errNoTestCase020BeforeClear'] = 
        Yaf_Application::app()->getLastErrorNo();
	$GLOBALS['errMessageTestCase020BeforeClear'] = 
        Yaf_Application::app()->getLastErrorMsg();
	Yaf_Application::app()->clearLastError();
    $GLOBALS['errNoTestCase020AfterClear'] = 
        Yaf_Application::app()->getLastErrorNo();
	$GLOBALS['errMessageTestCase020AfterClear'] = 
        Yaf_Application::app()->getLastErrorMsg();
}