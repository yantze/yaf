<?php
function error_handler($errno, $errstr, $errfile, $errline) {
    $GLOBALS['errMessageTestCase021'] = 'Error occurred instead of exception threw';
}