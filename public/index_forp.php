<?php
   forp_start();
   date_default_timezone_set('Asia/Chongqing');

   define('APPLICATION_PATH', dirname(__FILE__).'/../');
   define('APP_PATH', dirname(__FILE__).'/../');

   if(!extension_loaded("yaf")){
      include(APPLICATION_PATH.'/globals/framework/loader.php');
   }
   $application = new Yaf_Application( APPLICATION_PATH. "/conf/application.ini");

   $application->bootstrap()->run();
   forp_end();
?>

<script src="/public/js/forp.min.js"></script>
<script>
   (function($) {
      $(".forp")
      .forp({
         stack : <?php echo json_encode(forp_dump()); ?>,
         //mode : "fixed"
      })
   })(jMicro);
</script>
