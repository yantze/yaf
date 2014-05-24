<?php
class ControllerController extends Yaf_Controller_Abstract {
    public function actionAction() {
    }

    public function indexAction() {
        $this->forward("controller", "dummy");
        return false; /* don't auto-render */
    }

    public function dummyAction() {
        Yaf_Dispatcher::getInstance()->enableView();
    }
}
?>