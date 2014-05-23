<?php
class ControllerController extends Yaf_Controller_Abstract {
    public function init() {
        throw new Yaf_Exception("exception");
    }

    public function indexAction() {
        echo "okey";
        return FALSE;
    }
}