<?php
   Class AdminModel
   {
      protected $_table = "admin";

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

      public function select($username)
      {
         $sql = "select nickname, realname, email,is_del, password from ".$this->_table." where username='$username';";
         $result = $this->_db->query($sql)->fetchAll();

         if($result==null)
         {
            return false;
         }
         else
         {
            return $result[0];
         }
      }

      public function insert($info)
      {
         $result = $this->_db->insert($this->_table, $info);

         return $result==null?false:true;
      }
      public function update($info)
      {
         $result = $this->_db->update($this->_table, $info);

         return $result==null?false:true;
      }
      public function del($username)
      {
         $result = $this->_db->update($this->_table, array( 'is_del'=>'1'), array( 'username'=>$username );

         return $result==null?false:true;
      }
   }
