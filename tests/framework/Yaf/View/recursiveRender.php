<?php
if (isset($argv[1])) {
    $_GET['mode']=$argv[1];
}
if (isset($_GET['mode']) && $_GET['mode'] == 'yaf') {
    include('bootstrap_yaf_so.php');
} else { 
    include('bootstrap.php');
} 
$view = new Yaf_View_Simple(
            TEST_APPLICATION_PATH.'application/views/'
        );
$view->render('test/testCase039.phtml', array('tpl'=>'test/testCase038.phtml'));        
?>