<?php
   Class Utils
   {
      function guid(){
         if (function_exists('com_create_guid')){
            return com_create_guid();
         }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
            return $uuid;
         }
      }

      function ret_json($code=-1, $msg='未知错误'){
         $ret['code'] = $code;
         $ret['msg'] =  $msg;

         return json_encode($ret);
      }
   }
