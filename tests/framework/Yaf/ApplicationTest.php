<?php
/**
 * @group Yaf
 * @group YafApplication
 */
class YafApplicationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testCase014()
    {
        $app = new Yaf_Application(
            dirname(__FILE__) . "/_files/simple.ini",
            'product'
        );
        $config = new Yaf_Config_Ini(
            dirname(__FILE__) . "/_files/simple.ini", 'product'
        );
        $this->assertEquals($config->toArray(), $app->getConfig()->toArray());
        $this->assertEquals(
            APPLICATION_PATH.'/application', $app->getAppDirectory()
        );
        $this->assertEquals(array('Index'), $app->getModules());

    }

    /**
     * @runInSeparateProcess
     */
    public function testCase020()
    {
        $config = array(
            "application" => array(
                "directory" => TEST_APPLICATION_PATH,
                "dispatcher" => array(
                    "catchException" => 0,
                    "throwException" => 0,
                ),
            ),
        );
        $app = new Yaf_Application($config);
        include(dirname(__FILE__).'/_files/testCase020.php');
        $app->getDispatcher()->setErrorHandler(
            "error_handlerTestCase020", E_ALL
        );
        $app->run();
        if (!defined('YAF_MODE')) {
            $this->assertEquals(
                256,
                $GLOBALS['errNoTestCase020BeforeClear']
            );
        } else {
            $this->assertEquals(
                516,
                $GLOBALS['errNoTestCase020BeforeClear']
            );
        }
        $this->assertEquals(
            'Could not find controller script '.
            TEST_APPLICATION_PATH.'controllers/Index.php',
            $GLOBALS['errMessageTestCase020BeforeClear']
        );
        $this->assertEquals(
            0,
            $GLOBALS['errNoTestCase020AfterClear']
        );
        $this->assertEmpty($GLOBALS['errMessageTestCase020AfterClear']);

    }
    /**
     * @runInSeparateProcess
     */
    public function testCase021()
    {
        $app = new Yaf_Application(
            dirname(__FILE__) . "/_files/simple.ini",
            'nocatch'
        );
        $GLOBALS['errMessageTestCase021'] = '';
        $this->assertEmpty($GLOBALS['errMessageTestCase021']);
        include(dirname(__FILE__).'/_files/testCase021.php');
        $app->getDispatcher()
            ->setErrorHandler(
                "error_handler", E_RECOVERABLE_ERROR|E_USER_ERROR|E_USER_WARNING
            );
        $app->run();
        $this->assertEquals(
            'Error occurred instead of exception threw',
            $GLOBALS['errMessageTestCase021']
        );
    }
    /**
     * @runInSeparateProcess
     */
    public function testCase022()
    {
        $config = array(
            "application" => array(
                "directory" => TEST_APPLICATION_PATH,
                "dispatcher" => array(
                    "catchException" => 0,
                    "throwException" => 0,
                ),
            ),
        );

        $app = new Yaf_Application($config);
        $this->assertEquals(
            rtrim(TEST_APPLICATION_PATH, DIRECTORY_SEPARATOR),
            $app->getAppDirectory()
        );
        $app->setAppDirectory('/tmp');
        $this->assertEquals('/tmp', $app->getAppDirectory());
        try {
            $app->run();
        } catch (PHPUnit_Framework_Error $e) {
            $this->assertContains(
                'Could not find controller script /tmp/controllers/Index.php',
                $e->getMessage()
            );
        }
    }

    /**
     * @runInSeparateProcess
     */
    public function testCase042()
    {
        $config = array(
            "application" => array(
                "directory" => TEST_APPLICATION_PATH,
                "dispatcher" => array(
                    "catchException" => 0,
                    "throwException" => 1,
                ),
                "modules" => "module",
            ),
        );

        $app = new Yaf_Application($config);
        include(dirname(__FILE__).'/_files/testCase042.php');
        $request = new Yaf_Request_Http("/module/controller/index");
        try {
          $app->getDispatcher()->returnResponse(false)->dispatch($request);
        } catch (Yaf_Exception $e) {
          $this->assertEquals('exception', $e->getMessage());
        }
    }
}