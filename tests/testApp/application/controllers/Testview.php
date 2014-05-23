<?php
class TestviewController extends Yaf_Controller_Abstract {
    public function actionAction() {
    }

    public function indexAction() {
        Yaf_Dispatcher::getInstance()->disableView();
        $this->forward("dummy");
    }

    public function dummyAction() {
        Yaf_Dispatcher::getInstance()->enableView();
    }
}