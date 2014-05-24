<?php
   Class ProductModel
   {
      protected $_table = "shop_product";
      protected $_index = "product_id";

      public function __construct()
      {
         $this->_db = Yaf_Registry::get('_db');
      }

      public function select($username)
      {
         $params = array(
            "product_id",
            "product_name",
            "reg_time",
            "money",
            "amount",
            "category_id"
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
            "product_id",
            "product_name",
            "reg_time",
            "money",
            "amount",
            "category_id"
         );
         $whereis = array( "is_del"=>0);
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
         $result = $this->_db->update($this->_table, $info, array( 'product_id'=>$username ));

         return $result<1?false:true;
      }
      public function del($username)
      {
         $params = array( 'is_del'=>'1' );
         $whereis = array( 'product_id'=>$username );
         $result = $this->_db->update($this->_table, $params, $whereis );
         return $result==null?false:true;
      }
   }
         //print_r($this->_db->error());
