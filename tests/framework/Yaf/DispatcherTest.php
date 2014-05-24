<?php
/**
 * @group Yaf
 * @group YafDispatcher
 * @runTestsInSeparateProcesses
 */
class YafDispatcherTest extends PHPUnit_Framework_TestCase
{
    /**
     * Setup the loader
     * @var Yaf_Dispatcher
     */
    public $dispatcher=null;

    public function testAutoRender()
    {
        $this->dispatcher = Yaf_Dispatcher::getInstance();
        $this->assertTrue(
            $this->readAttribute($this->dispatcher, '_auto_render')
        );
        $this->dispatcher->autoRender(false);
        $this->assertFalse(
            $this->readAttribute($this->dispatcher, '_auto_render')
        );
        $this->dispatcher->autoRender(true);
        $this->assertTrue(
            $this->readAttribute($this->dispatcher, '_auto_render')
        );
    }

    public function testDisableView()
    {
        $this->dispatcher = Yaf_Dispatcher::getInstance();
        $this->assertTrue(
            $this->readAttribute($this->dispatcher, '_auto_render')
        );
        $this->dispatcher->disableView();
        $this->assertFalse(
            $this->readAttribute($this->dispatcher, '_auto_render')
        );
    }

    public function testEnableView()
    {
        $this->dispatcher = Yaf_Dispatcher::getInstance();
        $this->dispatcher->disableView();
        $this->assertFalse(
            $this->readAttribute($this->dispatcher, '_auto_render')
        );
        $this->dispatcher->enableView();
        $this->assertTrue(
            $this->readAttribute($this->dispatcher, '_auto_render')
        );
    }

    public function testFlushInstantly()
    {
        $this->dispatcher = Yaf_Dispatcher::getInstance();
        $this->assertFalse(
            $this->readAttribute($this->dispatcher, '_instantly_flush')
        );
        $r = $this->dispatcher->flushInstantly(true);
        $this->assertTrue(
            $this->readAttribute($this->dispatcher, '_instantly_flush')
        );
    }

    public function testGetApplication()
    {
        //$app = $this->dispatcher->getApplication();
        //print "<pre>";print_r($app); print "</pre>";
    }

    public function testGetREquest()
    {

    }

    public function testGetRouter()
    {

    }

    public function testInitView()
    {

    }

    public function testRegisterPlugin()
    {

    }

    public function testReturnResponse()
    {

    }

    public function testSetDefaultAction()
    {
        $this->dispatcher = Yaf_Dispatcher::getInstance();
        $this->assertEquals(
            'index',
            $this->readAttribute($this->dispatcher, '_default_action')
        );
        $this->dispatcher->setDefaultAction('test');
        $this->assertEquals(
            'test',
            $this->readAttribute($this->dispatcher, '_default_action')
        );
        //set back to index
        $this->dispatcher->setDefaultAction('index');
    }

    public function testSetDefaultController()
    {
        $this->dispatcher = Yaf_Dispatcher::getInstance();
        $this->assertEquals(
            'Index',
            $this->readAttribute($this->dispatcher, '_default_controller')
        );
        $this->dispatcher->setDefaultController('test');
        $this->assertEquals(
            'Test',
            $this->readAttribute($this->dispatcher, '_default_controller')
        );
        //set back to index
        $this->dispatcher->setDefaultController('index');
    }

    public function testSetDefaultModule()
    {
        $app = new Yaf_Application(
            TEST_APPLICATION_PATH.'conf/application.ini',
            'production'
        );
        $this->dispatcher = Yaf_Dispatcher::getInstance();
        $this->assertEquals(
            'Index',
            $this->readAttribute($this->dispatcher, '_default_module')
        );
        $this->dispatcher->setDefaultModule('foo');
        $this->assertEquals(
            'Foo',
            $this->readAttribute($this->dispatcher, '_default_module')
        );
        //set back to index
        $this->dispatcher->setDefaultModule('index');
    }
    /**
     * @runInSeparateProcess
     */
    public function testCase031()
    {
        $config = array(
            "application" => array(
                "directory" => TEST_APPLICATION_PATH,
                "dispatcher" => array(
                    "defaultRoute" => array(
                       "type" => "map",
                       "delimiter" => '##',
                       "controllerPrefer" => 1,
                    ),
                 ),
            ),
        );

        $app = new Yaf_Application($config);
        $routes = $app->getDispatcher()->getRouter()->getRoutes();
        $this->assertTrue(isset($routes['_default']));
        $this->assertInstanceOf('Yaf_Route_Map', $routes['_default']);
    }
}