<?php
/**
 * @group Yaf
 * @group YafRequest
 * @group YafRequestSimple
 */
class YafRequestSimpleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Yaf_Request_Simple
     */
    public $request=null;
    public function setUp()
    {
        // view
        $this->request = new Yaf_Request_Simple(
            'CLI', 'index', 'dummy', 'index'
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function testCase02()
    {
        if (!defined('YAF_MODE')) {
            //@todo for some reason this need to b true to pass the test
            Yaf_G::iniSet('yaf.use_spl_autoload', true);
            Yaf_G::iniSet('yaf.lowcase_path', false);
        } else {
            ini_set('yaf.use_spl_autoload', false);
            ini_set('yaf.lowcase_path', false);
        }
        $this->assertEquals('CLI', $this->request->getMethod());
        $this->assertEquals('index', $this->request->getModuleName());
        $this->assertEquals('dummy', $this->request->getControllerName());
        $this->assertEquals('index', $this->request->getActionName());
        $this->assertFalse($this->request->isDispatched());
        $this->assertTrue($this->request->isRouted());
        $this->request->setParam('name', 'Laruence');
        $this->assertEquals('Laruence', $this->request->getParam('name'));
        $this->assertNull($this->request->getParam('non-exists'));
        $this->assertTrue($this->request->isCli());
        $app =  new Yaf_Application(
            array(
                "application" => array(
                    "directory" => dirname(__FILE__)
                )
            )
        );

        try {
            $app->getDispatcher()->dispatch($this->request);
            $this->fail(
                'An Yaf_Exception_LoadFailed_Controller '.
                'exception was not throwed'
            );
        } catch (Exception $e) {
            $this->assertEquals(
                'Could not find controller script '.dirname(__FILE__).
                '/controllers/Dummy.php',
                $e->getMessage()
            );
        }
    }

}