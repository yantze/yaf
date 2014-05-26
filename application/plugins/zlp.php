<?php
   //require "../yaf_classes.php";
   /** 
   * 插件类定义
   * UserPlugin.php
   */
   class zlpPlugin extends Yaf_Plugin_Abstract {
      public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
      }
      public function routerShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {

      }

      public function render( $data = NULL , $layout = NULL , $sharp = 'default' )
      {
         if( $layout == null )
         {
            if( is_ajax_request() )
            {
               $layout = 'ajax';
            }
            elseif( is_mobile_request() )
            {
               //$layout = 'mobile';
               $layout = 'web';
            }
            else
            {
               $layout = 'web';
            }
         }

         $layout_file = AROOT . 'view/layout/' . $layout . '/' . $sharp . '.tpl.html';
         if( file_exists( $layout_file ) )
         {
            @extract( $data );
            require( $layout_file );
         }
      }
      function is_ajax_request()
      {
         $headers = apache_request_headers();
         return (isset( $headers['X-Requested-With'] ) && ( $headers['X-Requested-With'] == 'XMLHttpRequest' )) || (isset( $headers['x-requested-with'] ) && ($headers['x-requested-with'] == 'XMLHttpRequest' ));
      }

      function is_mobile_request()
      {
         $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';

         $mobile_browser = '0';

         if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
         $mobile_browser++;

         if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))
         $mobile_browser++;

         if(isset($_SERVER['HTTP_X_WAP_PROFILE']))
         $mobile_browser++;

         if(isset($_SERVER['HTTP_PROFILE']))
         $mobile_browser++;

         $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
         $mobile_agents = array(
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
            'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
            'wapr','webc','winw','winw','xda','xda-'
         );

         if(in_array($mobile_ua, $mobile_agents))
            $mobile_browser++;

         if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
            $mobile_browser++;

         // Pre-final check to reset everything if the user is on Windows
         if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)
            $mobile_browser=0;

         // But WP7 is also Windows, with a slightly different characteristic
         if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)
            $mobile_browser++;

         if($mobile_browser>0)
            return true;
         else
            return false;
      }
   }
