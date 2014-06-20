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
         $this->getView()->assign("action",strtolower(
            $this->getRequest()->getControllerName().'_'.
            $this->getRequest()->getActionName()));

            $this->getView()->assign("productData", $this->_product->SelectAll());

         return true;
      }


      function addAction()
      {
         $this->getView()->assign("action",strtolower(
            $this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName()));

         if($this->getRequest()->isPost())
         {
            $posts = $this->getRequest()->getPost();
            unset($posts['submit']);
            foreach($posts as $v){
               if(empty($v)){
                  exit("不能为空");
               }
            }
            $_utils = new utils();
            $posts['product_uuid']=$_utils->guid();
            $posts['is_del']='';
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
            if($this->_product->update($posts['product_name'],$posts)){
               exit("修改商品成功");
            }else{
               exit("修改商品失败");
            }
         }
     }

      function delAction()
      {
          if($this->getRequest()->isPost()){
            $product_id = $this->getRequest()->getPost("product_name");
            if($this->_product->del($product_id)){
               exit("删除成功");
            }else{
               exit("删除失败");
            }
         }
      }

      function getAction()
      {
         $product_name = $this->getRequest()->getQuery("product_name");
         if($product_data = $this->_product->select($product_name)){
            print_r($product_data);
            return false;
         }else{
            exit("查找失败");
         }
      }

      function uploadAction()
      {
         chdir(APP_PATH);
         // echo getcwd();

         $uploads_dir = 'public/images/product/';
         // print_r(array_keys($_FILES));
         //echo $_FILES['file_pic']['tmp_name'];
         $filename = array_keys($_FILES);
         $type = $_FILES[$filename[0]]['type'];
         $subfix = '';

         if ( $type == 'image/jpeg' ||  $type == 'image/png'  )
         {
            $subfix = '.jpg';
         }
         move_uploaded_file($_FILES[$filename[0]]['tmp_name'], $uploads_dir . $filename[0] . $subfix );

         return false;
      }

      function listAction()
      {
         $this->getView()->assign("action",strtolower(
            $this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName()));

         $productData = $this->_product->selectAll();
         $this->getView()->assign("productData",$productData);

         if ($this->getRequest()->isXmlHttpRequest()){
            echo json_encode($productData);
            return false;
         }

         return true;
      }

   }


