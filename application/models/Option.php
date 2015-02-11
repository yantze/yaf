<?php
   /**
   * @name OptionModel
   * @author yantze
   * @desc 商品的数据库操作model
   */
   Class OptionModel
   {
      protected $_table = "shop_option";

      public function __construct()
      {
         $this->_db = Yaf_Registry::get('_db');
      }

      public function selectAll()
      {
         $params = array(
            "name",
            "value"
         );
         $result = $this->_db->select($this->_table, $params );

         return $result==null?false:$result;
      }
   }
