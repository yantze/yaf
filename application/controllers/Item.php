<?php
   //require "../yaf_classes.php";
   Class ItemController extends Yaf_Controller_Abstract
   {
      public function init()
      {
         $this->_item = new ProductModel();
      }

      function indexAction()
      {
         $this->getView()->assign("action",strtolower(
            $this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName()));

         $productData = $this->_item->selectAll();
         $this->getView()->assign("itemAll",$productData);

         return true;
      }

      function getAction()
      {
         $product_name = $this->getRequest()->getQuery("name");
         if($item = $this->_item->select($product_name)){
            $this->getView()->assign("product", $item[0]);
            return true;
         }else{
            exit("查找失败");
         }
      }

      function addCart() { }

      function addFav() { }


   }


