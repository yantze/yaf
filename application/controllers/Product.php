<?php
   //require "../yaf_classes.php";
   Class ProductController extends Yaf_Controller_Abstract
   {
      public function init()
      {
         $this->_product = new ProductModel();
      }

      function indexAction()
      {
         $productData = $this->_product->selectAll();
         $this->getView()->assign("productData",$productData);
         print_r($productData);
         Yaf_Dispatcher::getInstance()->disableView();
      }

      function addAction()
      {
      }

      function editAction()
      {
      }

      function delAction()
      {
      }

      function getAction()
      {
      }

   }


