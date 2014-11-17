<?php
   //require "../yaf_classes.php";
   Class FavController extends Yaf_Controller_Abstract
   {
      public function init()
      {
         $this->_fav = new FavModel();
         $this->_util = new utils();
      }

      function indexAction()
      {
         return true;
      }

	   public function addAction()
      {
         $posts['product_uuid'] = $this->getRequest()->getQuery("name");
         $posts['user_uuid'] = Yaf_Session::getInstance()->get("user_uuid");

         if($this->_fav->insert($posts)){
            if( $this->getRequest()->isXmlHttpRequest() ){
               exit($this->_util->ret_json(1,"收藏商品成功"));
            }

            // $this->forward("index", "fav", "list" );
            $this->redirect("/fav/list");
            return false;

         }else{
            if($posts['user_uuid']==NULL)
            {
               $this->forward("index", "user", "login" );
               return false;
            }
            if( $this->getRequest()->isXmlHttpRequest() ){
               exit($this->_util->ret_json(-1,"收藏商品失败"));
            }
         }
      }



      public function delAction()
      {
         $fav_id = $this->getRequest()->getQuery("name");
         if($this->_fav->del($fav_id)){
            exit($this->_util->ret_json(2,"删除商品成功"));
         }else{
            exit($this->_util->ret_json(-2,"删除商品失败"));
         }
      }

      public function listAction()
      {
         $where = Yaf_Session::getInstance()->get("user_uuid");
         $favData = $this->_fav->selectAll($where);
         $this->getView()->assign("items",$favData);

         return true;
      }

      public function addtocartAction()
      {
         $product_uuid = $this->getRequest()->getQuery("product_uuid");
         $params = array(
            'product_uuid'=>$product_uuid
         );
         $this->forward("index", "order", "add", $params );

         return false;
      }





   }


