<?php
   //require "../yaf_classes.php";
   /**
   * 
   */
   class AdminController extends Yaf_Controller_Abstract
   {
      public function init()
      {
      }

      /**
      * 显示用户信息
      *
      */
      public function delAction()
      {
         $this->getRequest()->setActionName("indexAction");
      }

      public function indexAction()
      {
         $this->getView()->assign("name",'yantze');
         $this->getView()->assign("content",'game,');

         $_SESSION['username'] = 'name';

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
   }
