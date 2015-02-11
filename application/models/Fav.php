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
      public function selectAll($where)
      {
        //实现左连接表查询
        $join = array(
            "[>]shop_product"=>"product_uuid"
        );

        $params = array(
            "fav_id",
            "product_name",
            "money",
            "product_uuid",
            "fav_time",
            "comment"
         );
         $whereis = array("user_uuid"=>$where);
         $result = $this->_db->select( "shop_fav" , $join, $params, $whereis );
         // print_r($this->_db->error());
         // print_r($result);

         return $result==null?false:$result;
      }

      public function insert($info)
      {
         $result = $this->_db->insert($this->_table, $info);
         // print_r($this->_db->error());

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
