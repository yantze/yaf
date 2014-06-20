<?php
   //require "../yaf_classes.php";
   class AdminController extends Yaf_Controller_Abstract
   {
      public function init(){
         //使用layout页面布局
         /*$this->_layout = new LayoutPlugin('admin/admin.html');
         $this->dispatcher = Yaf_Registry::get("dispatcher");
         $this->dispatcher->registerPlugin($this->_layout);*/

         $this->_user = new AdminModel();
      }

      public function indexAction()
      {
         $this->getView()->assign("action",strtolower(
            $this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName()));

         //判断是否登陆
         if(Yaf_Session::getInstance()->get("admin_username")){
            $this->getView()->assign("isLogin",true);
         } else{
            $this->getView()->assign("isLogin",false);
         }

         $this->getView()->assign("name",'yantze');
         $this->getView()->assign("content",'game,');

         $userData = $this->_user->selectAll();
         $this->getView()->assign("userData", $userData );

      }

      public function loginAction()
      {
         $this->getView()->assign("action",strtolower(
            $this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName()));

         if($this->getRequest()->isPost())
         {
            $username = $this->getRequest()->getPost('username');
            $pwd      = $this->getRequest()->getPost('password');

            $return  = $this->_user->loginUser($username, sha1(trim($pwd)));

            if($return)
            {
               //$this->getView()->assign("content",'登陆成功！！');
               //$_SESSION['username']=$username."ddd"; //这种方式已经不使用了
               Yaf_Session::getInstance()->set("admin_username",$username);
               //exit("登录成功！");
               $ret = $this->ret_api(0);
               exit($ret);
            }
            else
            {
               //$this->getView()->assign("content",'登陆不成功！！');
               //exit("登陆不成功！");
               $ret = $this->ret_api(1);
               exit($ret);
            }
         }

         return true;
      }

      public function addAction()
      {
         $this->getView()->assign("action",strtolower(
            $this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName()));

         if($this->getRequest()->isPost()){
            $posts = $this->getRequest()->getPost();
            $posts['password'] = sha1($posts['password']);
            $posts['repassword'] = sha1($posts['repassword']);
            foreach($posts as $v){
               if(empty($v)){
                  //exit("不能为空");
                  $ret = $this->ret_api(2);
                  exit($ret);
               }
            }
            if($posts['password'] != $posts['repassword']){
               $ret = $this->ret_api(3);
               exit($ret);
               //exit("两次密码不一致");
            }
            unset($posts['repassword']);
            unset($posts['submit']);
            $posts['is_del'] = 0;
            if($this->_user->insert($posts)){
               //exit("添加成功");
               $ret = $this->ret_api(4);
            }else{
               $ret = $this->ret_api(5);
               //exit("添加失败");
            }
            exit($ret);
         }
      }
      public function editAction()
      {
         if($this->getRequest()->isPost()){
            $posts = $this->getRequest()->getPost();
            $posts['password'] = sha1($posts['password']);
            $posts['repassword'] = sha1($posts['repassword']);
            foreach($posts as $v){
               if(empty($v)){
                  //exit("不能为空");
                  $ret = $this->ret_api(2);
                  exit($ret);
               }
            }
            if($posts['password'] != $posts['repassword']){
               $ret = $this->ret_api(3);
               exit($ret);
            }
            $username = $posts['username'];
            unset($posts['repassword']);
            unset($posts['submit']);
            unset($posts['username']);
            $posts['is_del'] = 0;
            if($this->_user->update($username, $posts)){
               $ret = $this->ret_api(5);
            }else{
               $ret = $this->ret_api(6);
            }
            exit($ret);
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
                  $ret = $this->ret_api(5);
               }else{
                  $ret = $this->ret_api(6);
               }
            }
            $ret = $this->ret_api(7);
            exit($ret);
         }
         return false;
      }

      public function LogoutAction()
      {
         //unset($_SESSION['username']);
         Yaf_Session::getInstance()->del("admin_username");
         header('Location:/admin/');
      }

      public function ret_api($code=-1)
      {
         $ret=array();
         $ret[-1]['ret_type']='error_response';
         $ret[-1]['msg']='未知错误';

         $ret[0]['ret_type']='success_response';
         $ret[0]['msg']='登陆成功';

         $ret[1]['ret_type']='error_response';
         $ret[1]['msg']='登陆不成功';

         $ret[2]['ret_type']='error_response';
         $ret[2]['msg']='不能为空';

         $ret[3]['ret_type']='error_response';
         $ret[3]['msg']='两次密码不一致';

         $ret[4]['ret_type']='success_response';
         $ret[4]['msg']='添加成功';

         $ret[5]['ret_type']='error_response';
         $ret[5]['msg']='添加失败';

         $ret[6]['ret_type']='success_response';
         $ret[6]['msg']='修改成功';

         $ret[7]['ret_type']='error_response';
         $ret[7]['msg']='删除失败';


         $util = new utils();
         return $util->ret_json($code, $ret[$code]['msg']);
      }
   }
