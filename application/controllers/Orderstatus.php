<?php
   //require "../yaf_classes.php";
   Class OrderstatusController extends Yaf_Controller_Abstract
   {
      public function init()
      {
         $this->_orderStatus = new OrderStatusModel();
      }

      function indexAction()
      {
         return false;
      }

      public function addAction()
      {
         $posts['order_serial'] = Yaf_Session::getInstance()->get("order_serial");
         $posts['status'] = "已成功送到";

         //生成新的购物车序列号
         $order_serial = date('U').'98'.rand(10000,99999);
         Yaf_Session::getInstance()->set("order_serial",$order_serial);

         if($this->_orderStatus->insert($posts)){
            exit("结算成功");
         }else{
            exit("结算失败");
         }

         return false;
      }



      public function delAction()
      {
         $order_id = $this->getRequest()->getQuery("name");
         if($this->_orderStatus->del($order_id)){
            exit("删除订单成功");
         }else{
            exit("删除订单失败");
         }

         return false;
      }

      public function listAction()
      {
         $orderstatusData = $this->_orderStatus->selectAll();
         $this->getView()->assign("items",$orderstatusData);

      }


   }


