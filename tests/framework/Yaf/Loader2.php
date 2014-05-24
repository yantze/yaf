<?php
/**
 * @group Yaf
 * @group YafLoader2
 */
class YafLoader2Test extends PHPUnit_Framework_TestCase
{
    /**
     * @runaInSeparateProcess
     */
    public function testCase027()
    {
        $globalDir = '/php/global/dir';
        if (!defined('YAF_MODE')) {
            Yaf_G::iniSet('yaf.library', $globalDir);
            Yaf_G::iniSet('yaf.use_spl_autoload', false);
        } else {
            ini_set('yaf.library', $globalDir);
            ini_set('yaf.use_spl_autoload', false);
        }
        $config = array(
            "application" => array(
                "directory" => TEST_APPLICATION_PATH.'application/',
                "dispatcher" => array(
                    "catchException" => 0,
                    "throwException" => 0
                )
            )
        );
        $app = new Yaf_Application($config);
        include(dirname(__FILE__).'/Loader/testCase027.php');
        try {
            $app->execute('testCase027');
        } catch (PHPUnit_Framework_Error_Warning $e) {
            $this->assertContains(
                'Could not find script '.TEST_APPLICATION_PATH.
                'application/controllers/NoExists.php',
                $e->getMessage()
            );
        }
    }
    
}
