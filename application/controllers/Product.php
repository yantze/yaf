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

         return false;
      }

      function addAction()
      {
         if($this->getRequest()->isPost())
         {
            $posts = $this->getRequest()->getPost();
            unset($posts['submit']);
            foreach($posts as $v){
               if(empty($v)){
                  exit("不能为空");
               }
            }
            if($this->_product->insert($posts)){
               exit("添加商品成功");
            }else{
               exit("添加商品失败");
            }
         }
      }

      function editAction()
      {
          if($this->getRequest()->isPost())
         {
            $posts = $this->getRequest()->getPost();
            unset($posts['submit']);
            foreach($posts as $v){
               if(empty($v)){
                  exit("不能为空");
               }
            }
            if($this->_product->insert($posts)){
               exit("修改商品成功");
            }else{
               exit("修改商品失败");
            }
         }
     }

      function delAction()
      {
          if($this->getRequest()->isPost()){
            $product_id = $this->getRequest()->getPost("product_id");
            if($this->_product->del($product_id)){
               exit("删除成功");
            }else{
               exit("删除失败");
            }
         }
      }

      function getAction()
      {
         $product_id = $this->getRequest()->getQuery("product_id");
         if($product_data = $this->_product->select($product_id)){
            print_r($product_data);
            return false;
         }else{
            exit("查找失败");
         }
      }

   }


