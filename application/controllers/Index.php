<?php
   /**
   * @name IndexController
   * @author yantze
   * @desc 默认控制器
   * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
   */
   class IndexController extends Yaf_Controller_Abstract {

      /** 
      * 默认动作
      * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
      * 对于如下的例子, 当访问http://yourhost/y/index/index/index/name/yantze 的时候, 你就会发现不同
      */
      public function indexAction()
      {
         $this->getView()->assign("name",'yantze');
         $this->getView()->assign("content",'game,');

               $_SESSION['username'] = 'name';
               echo $_SESSION['username'];

         if($this->getRequest()->isPost())
         {
            $username = $this->getRequest()->getPost('username');
            $pwd      = $this->getRequest()->getPost('password');

            $user = new UserModel();
            $ret  = $user->loginUser($username, md5(trim($pwd)));

            if($ret)
            {
               //$this->getView()->assign("content",'登陆成功！！');
               $_SESSION['username']=$username;
               exit("100:登录成功！");
            }
            else
            {
               //$this->getView()->assign("content",'登陆不成功！！');
               exit("101:登陆不成功！");
            }

         }

      }

      public function testAction($name = "Stranger", $password = "") {
               echo $_SESSION['username'];
         //1. fetch query
         $get = $this->getRequest()->getQuery("get", "default value");

         //这个可以获取两种方式提交的name和value
         //print_r( $this->getRequest()->getParams($name,'ddd'));

         echo $this->getRequest()->getMethod();

         //2. fetch model
         $model = new SampleModel();


         //3. assign
         $this->getView()->assign("content", $model->selectSample());
         $this->getView()->assign("name", $name);

         //$this->display("index",array("name"=>ddddddddd));

         //4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板

         //$this->db = Yaf_Registry::get('_db');
         //print_r($this->db);
         return TRUE;
      }
   }
