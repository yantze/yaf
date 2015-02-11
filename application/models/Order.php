<?php
   //require "../yaf_classes.php";
   Class OrderModel
   {
      protected $_table = "shop_order";
      protected $_index = "order_id";

      public function __construct()
      {
         $this->_db = Yaf_Registry::get('_db');
      }

      public function select($username)
      {
         $params = array(
            "order_id",
            "user_uuid",
            "product_uuid",
            "reg_time",
            "order_serial"
         );
         $whereis = array( $this->_index=>$username );
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
            "order_id",
            "product_name",
            "product_uuid",
            "money",
            "shop_order.reg_time"
         );
         $whereis = array(
            "order_serial"=>$where
         );

         $result = $this->_db->select( "shop_order" , $join, $params, $whereis );
         // print_r($this->_db->error());
         // print_r($result);
         return $result==null?false:$result;
      }

      public function insert($info)
      {
         $result = $this->_db->insert($this->_table, $info);

         return $result<1?false:true;
      }
      public function update($username, $info)
      {
         $whereis = array( $this->_index=>$username );
         $result = $this->_db->update($this->_table, $info, $whereis );

         return $result<1?false:true;
      }
      public function del($username)
      {
         $whereis = array( $this->_index=>$username );
         $result = $this->_db->delete($this->_table, $whereis );
         return $result==null?false:true;
      }
   }
