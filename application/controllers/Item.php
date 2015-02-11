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
            $this->getView()->assign("item", $item[0]);
         }else{
            exit("查找失败");
         }

         return true;
      }

      //分类的分页未实现,减少代码:)
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
         $page = $this->getRequest()->getQuery("page");
         $size = $this->getRequest()->getQuery("size");

         if(!($page&&$size)){
            $page=1;
            $size=12;
         }


         $product_name = $this->getRequest()->getQuery("name");
         if( $this->_item->select_name($product_name) ){
            $maxNum   = $this->_item->selectAll_num_byName($product_name);

            $items = $this->_item->selectPage_byName($product_name, $page, $size);
            $this->getView()->assign("items", $items);

            $this->getView()->assign("maxNum",intval($maxNum));
            $this->getView()->assign("curPage",intval($page));
            $this->getView()->assign("curSize",intval($size));
            $this->getView()->assign("product_name",$product_name);
            // print_r($items);
         }else{
            $this->getView()->assign("error",'查找失败');
         }

         echo $this->render("../index/search");

         return false;
      }


      function addCart() { }

      function addFav() { }


   }


