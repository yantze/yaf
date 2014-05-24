<?php
   Class FavModel
   {
      protected $_table = "shop_fav";

      public function __construct()
      {
         $this->_db = Yaf_Registry::get('_db');
      }

      public function select($username)
      {
         $params = array(
            "fav_id",
            "user_uuid",
            "product_uuid",
            "fav_time",
            "comment"
         );
         $whereis = array( "fav_id"=>$username );
         $result = $this->_db->select($this->_table, $params ,$whereis );

         return $result==null?false:$result;
      }
      public function selectAll()
      {
         $params = array(
            "fav_id",
            "user_uuid",
            "product_uuid",
            "fav_time",
            "comment"
         );
         $whereis = array();
         $result = $this->_db->select($this->_table, $params, $whereis );

         return $result==null?false:$result;
      }

      public function insert($info)
      {
         $result = $this->_db->insert($this->_table, $info);

         return $result<1?false:true;
      }
      public function update($username, $info)
      {
         $result = $this->_db->update($this->_table, $info, array( 'fav_id'=>$username ));

         return $result<1?false:true;
      }
      public function del($username)
      {
         $whereis = array( 'fav_id'=>$username );
         $result = $this->_db->delete($this->_table, $whereis );
         return $result==null?false:true;
      }
   }
