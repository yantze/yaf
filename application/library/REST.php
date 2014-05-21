<?php  
   /* 
   reference : https://github.com/coderhwz/yaf-rest
   */

class RESTPlugin extends Yaf_Plugin_Abstract {

	protected $allowMethods = array('get','post');

	protected $denyRedirectURL = '';

	protected $denyMsg = '';

	public function preDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
		$actionName = $request->getActionName();

		$curMethod = strtolower($request->getMethod());

		if (in_array($curMethod,$this->allowMethods))
		{
			$request->setActionName($actionName . ucfirst($curMethod));

		}else{
			if ($this->denyRedirectURL)
			{
				$response->setRedirect($this->denyRedirectURL);
			} else{
				die($this->denyMsg);
			}
		}

	}

	/**
	 * 设置允许的请求方法
	 *
	 * @param $methods mixed (string| array)
	 * @return void
	 * @author hwz
	 **/
	public function setAllowMethods($methods) 
	{
		if (is_array($methods))
		{
			$methods = array_map('strtolower',$methods);
			$this->allowMethods = array_merge($methods);
		}else{
			$this->allowMethods[] = strtolower( $methods );
		}
	}

	/**
	 * 设置遇到不允许请求方法时,跳转的URL
	 *
	 * @param $url string 跳转地址
	 * @return void
	 * @author hwz
	 **/
	public function setDenyRedirectURL($url) 
	{
		$this->denyRedirectURL = $url;
	}

	/**
	 * 设置遇到不允许请求方法时,显示错误信息,如果没有设置跳转的话
	 *
	 * @param $msg string 错误信息
	 * @return void
	 * @author hwz
	 **/
	public function setDenyMsg($msg) 
	{
		$this->denyMsg = $msg;
	}
}
