<?php

class IndexController extends Yaf_Controller_Abstract {
	public function init() {
		$this->_view->assign(Yaf_Registry::get("config")->common->toArray());
	}

	public function indexAction() {
	}
}
