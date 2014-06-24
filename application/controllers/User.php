<?php
   //require "../yaf_classes.php";
   class UserController extends Yaf_Controller_Abstract
   {
      public function init()
      {
         $this->_user = new UserModel();
         $this->_util = new utils();
      }

      public function indexAction()
      {
         $this->getView()->assign("name",'yantze');
         $this->getView()->assign("content",'game,');

         $userData = $this->_user->selectAll();
         $this->getView()->assign("userData", $userData );


      }

      public function loginAction()
      {
         if($this->getRequest()->isPost())
         {
            $username = $this->getRequest()->getPost('username');
            $pwd      = $this->getRequest()->getPost('password');

            $ret  = $this->_user->loginUser($username, sha1(trim($pwd)));

            if($ret)
            {
               //$_SESSION['username']=$username."ddd"; //这种方式已经不使用了
               Yaf_Session::getInstance()->set("username",$username);

               //$ret如果是正确的，那么返回的是user_uuid
               Yaf_Session::getInstance()->set("user_uuid",$ret);
               $had_order_serial = Yaf_Session::getInstance()->get("order_serial");
               if(!$had_order_serial){
                  $order_serial = date('U').'98'.rand(10000,99999);
                  Yaf_Session::getInstance()->set("order_serial",$order_serial);
               }

               // exit("登录成功！");
               exit($this->_util->ret_json(0,"登陆成功"));
            }
            else
            {
               //$this->getView()->assign("content",'登陆不成功！！');
               exit($this->_util->ret_json(1,"登陆失败"));
            }
         }

         return true;
      }

      public function addAction()
      {
         if($this->getRequest()->isPost()){
            $posts = $this->getRequest()->getPost();
            $posts['password'] = sha1($posts['password']);
            $posts['repassword'] = sha1($posts['repassword']);
            foreach($posts as $v){
               if(empty($v)){
                  exit("不能为空");
               }
            }
            if($posts['password'] != $posts['repassword']){
               exit("两次密码不一致");
            }
            unset($posts['repassword']);
            unset($posts['submit']);
            $posts['is_del'] = '';
            $_utils = new utils();
            $posts['user_uuid'] = $_utils->guid();
            if($this->_user->insert($posts)){
               exit("添加成功");
            }else{
               exit("添加失败");
            }
         }
         return false;
      }
      public function editAction()
      {
         if($this->getRequest()->isPost()){
            $posts = $this->getRequest()->getPost();
            $posts['password'] = sha1($posts['password']);
            $posts['repassword'] = sha1($posts['repassword']);
            foreach($posts as $v){
               if(empty($v)){
                  exit("不能为空");
               }
            }
            if($posts['password'] != $posts['repassword']){
               exit("两次密码不一致");
            }
            $username = $posts['username'];
            unset($posts['repassword']);
            unset($posts['submit']);
            unset($posts['username']);
            $posts['is_del'] = 0;
            if($this->_user->update($username, $posts)){
               exit("修改成功");
            }else{
               exit("修改失败");
            }
         }
      }

      public function delAction()
      {
         if($this->getRequest()->isPost())
         {
            $username = $this->getRequest()->getPost('username');
            $password = $this->getRequest()->getPost('password');
            $password = sha1($password);
            if($this->_user->loginUser($username,$password))
            {
               if($this->_user->del($username)){
                  exit("删除成功");
               }else{
                  exit("删除失败");
               }
            }
            exit("删除失败");
         }
         return false;
      }

      public function LogoutAction()
      {
         unset($_SESSION['username']);
         unset($_SESSION['user_uuid']);
         unset($_SESSION['order_serial']);
         header('Location:/index/');
      }

   }
