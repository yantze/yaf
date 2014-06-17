<?php
   //require "../yaf_classes.php";
   Class OrderController extends Yaf_Controller_Abstract
   {
      public function init()
      {
         $this->_order = new OrderModel();
      }

      function indexAction()
      {
         return true;
      }

      public function addAction()
      {
         $posts['product_uuid'] = $this->getRequest()->getQuery("name");
         $posts['user_uuid'] = Yaf_Session::getInstance()->get("user_uuid");
         $posts['order_serial'] = Yaf_Session::getInstance()->get("order_serial");

         if($this->_order->insert($posts)){
            exit("添加商品成功");
         }else{
            exit("添加商品失败");
         }

         return false;
      }



      public function delAction()
      {
         $order_id = $this->getRequest()->getQuery("name");
         if($this->_order->del($order_id)){
            exit("删除商品成功");
         }else{
            exit("删除商品失败");
         }

         return false;
      }

      public function listAction()
      {
         $where = Yaf_Session::getInstance()->get("order_serial");
         $orderData = $this->_order->selectAll($where);
         $this->getView()->assign("items",$orderData);

         return true;
      }


   }


