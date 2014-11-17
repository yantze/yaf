<?php
   //require "../yaf_classes.php";
   Class OrderController extends Yaf_Controller_Abstract
   {
      public function init()
      {
         $this->_order = new OrderModel();
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
         $posts['order_serial'] = Yaf_Session::getInstance()->get("order_serial");

         if($this->_order->insert($posts)){
            if($this->getRequest()->isXmlHttpRequest()){
               exit($this->_util->ret_json(1,"添加商品成功"));
            }
            // $this->forward("order","list");
            $this->redirect("/order/list");
            /*
             * 这里这两种方法(forward,redirect)都各自有弊端
             * forward导致重复添加商品
             * redirect导致修改路由的时候，这里要重新修改
             */
         }else{
            if($this->getRequest()->isXmlHttpRequest()){
               exit($this->_util->ret_json(-1,"添加商品失败"));
            }
            if($posts['user_uuid']==NULL)
            {
               $this->forward("index", "user", "login" );
               return false;
            }
         }

         return false;
      }



      public function delAction()
      {
         $order_id = $this->getRequest()->getQuery("name");
         if($this->_order->del($order_id)){
            exit($this->_util->ret_json(0,"删除商品成功"));
         }else{
            exit($this->_util->ret_json(0,"删除商品失败"));
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


