<?php
class ErrorController extends yaf_controller_abstract {
	public function errorAction($exception) {
		$this->getView()->assign("exception", $exception);
	}

}
