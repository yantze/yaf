<?php
   Class AdminModel
   {
      protected $_table = "shop_admin";
      protected $_index = "username";

      public function __construct()
      {
         $this->_db = Yaf_Registry::get('_db');
      }

      public function loginUser($username, $password)
      {
         $params = array(
            "password",
            "email",
            "create_time"
         );
         $whereis = array( 
            "AND"=>array( $this->_index=>$username, "is_del"=>0)
         );
         $result = $this->_db->select($this->_table, $params ,$whereis );
         if($result==null)
         {
            return false;
         }
         else if ( $result[0]['password'] == ($password) )
            return true;
         else
            return false;
      }
      public function select($username)
      {
         $params = array(
            "password",
            "email",
            "create_time"
         );
         $whereis = array( 
            "AND"=>array( $this->_index=>$username, "is_del"=>0)
         );
         $result = $this->_db->select($this->_table, $params ,$whereis );

         return $result==null?false:$result;
      }
      public function selectAll()
      {
         $params = array(
            "username",
            "email",
            "create_time"
         );
         $result = $this->_db->select($this->_table, $params );

         return $result==null?false:$result;
      }

      public function insert($info)
      {
         //$result = $this->_db->insert($this->_table, $info, $whereis);
         $sql = "REPLACE INTO ".$this->_table."(username, email, password, is_del) VALUES('".$info['username']."', '".$info['email']."', '".$info['password']."', b'0');";
         $result = $this->_db->exec($sql);

         return $result<1?false:true;
      }
      public function update($username, $info)
      {
         $result = $this->_db->update($this->_table, $info, array( $this->_index=>$username ));

         return $result<1?false:true;
      }
      public function del($username)
      {
         $params = array( 'is_del'=>'1' );
         $whereis = array( $this->_index=>$username );
         $result = $this->_db->update($this->_table, $params, $whereis );
         return $result==null?false:true;
      }
   }
