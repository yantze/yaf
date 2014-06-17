<?php
   //require "../yaf_classes.php";
   Class FavController extends Yaf_Controller_Abstract
   {
      public function init()
      {
         $this->_fav = new FavModel();
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
            exit("收藏商品成功");
         }else{
            exit("收藏商品失败");
         }
      }



      public function delAction()
      {
         $fav_id = $this->getRequest()->getQuery("name");
         if($this->_fav->del($fav_id)){
            exit("删除收藏成功");
         }else{
            exit("删除收藏失败");
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


