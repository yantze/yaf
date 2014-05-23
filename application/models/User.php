<?php
   class UserModel
   {
        protected $_table = "shop_admin";

      public function __construct()
      {
         $this->_db = Yaf_Registry::get('_db');
      }

      public function loginUser($username, $password)
      {
         $sql = "select nickname, realname, email,is_del, password from ".$this->_table." where username='$username';";
         $result = $this->_db->query($sql)->fetchAll();

         if($result==null)
         {
            return false;
         }
         else if ($result[0]['password'] == $password)
            return true;
         else
            return false;
      }

   }

