<?php

   class TestController extends Yaf_Controller_Abstract {
      public function smartyAction($name = "Stranger", $password = "") {
         $this->getView()->assign("body", "Hello Wrold<br/>");
      }
      public function twigAction() {
         $this->getView()->assign("body", "Hello Wrold<br/>");
      }
   }


