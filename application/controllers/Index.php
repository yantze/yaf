<?php
   /**
   * @name IndexController
   * @author yantze
   * @desc 默认控制器
   * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
   */
   class IndexController extends Yaf_Controller_Abstract {

      /** 
      * 默认动作
      * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
      * 对于如下的例子, 当访问http://yourhost/y/index/index/index/name/yantze 的时候, 你就会发现不同
      */
      public function indexAction()
      {
         $site = new OptionModel();
         $product = new ProductModel();


         $page = $this->getRequest()->getQuery("page");
         $size = $this->getRequest()->getQuery("size");

         if(!($page&&$size)){
            $page=1;
            $size=12;
         }

         $itemlist = $product->selectPage($page, $size);
         $maxNum   = $product->selectAll_num();
         $siteInfo = $site->selectAll();

         $this->getView()->assign("name",$siteInfo[0]['value']);
         $this->getView()->assign("desc",$siteInfo[1]['value']);
         $this->getView()->assign("items",$itemlist);
         $this->getView()->assign("maxNum",intval($maxNum));
         $this->getView()->assign("curPage",intval($page));
         $this->getView()->assign("curSize",intval($size));

         return true;
      }

      public function testAction($name = "Stranger", $password = "") {
         echo $_SESSION['username'];
         //1. fetch query
         $get = $this->getRequest()->getQuery("get", "default value");

         //这个可以获取两种方式提交的name和value
         //print_r( $this->getRequest()->getParams($name,'ddd'));

         echo $this->getRequest()->getMethod();

         //2. fetch model
         $model = new SampleModel();


         //3. assign
         $this->getView()->assign("content", $model->selectSample());
         $this->getView()->assign("name", $name);

         //$this->display("index",array("name"=>ddddddddd));

         //4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板

         //$this->db = Yaf_Registry::get('_db');
         //print_r($this->db);
         return TRUE;
      }
   }
