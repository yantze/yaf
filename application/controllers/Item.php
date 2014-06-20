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

      function categoryAction()
      {
         $category_id = $this->getRequest()->getQuery("id");
         if($product_data = $this->_item->select_category($category_id)){
            $this->getView()->assign("items", $product_data);
         }else{
            $this->getView()->assign("error",'查找失败');
         }

         echo $this->render("../index/search");

         return false;
      }

      function searchAction()
      {
         $product_name = $this->getRequest()->getQuery("name");
         if($product_data = $this->_item->select_name($product_name)){
            $this->getView()->assign("items", $product_data);
         }else{
            $this->getView()->assign("error",'查找失败');
         }

         echo $this->render("../index/search");

         return false;
      }


      function addCart() { }

      function addFav() { }


   }


