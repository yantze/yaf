<?php
/**
 * @group Yaf
 * @group YafRoute
 * @group YafRouteSupervar
 * @runTestsInSeparateProcesses
 */
class YafRouteSupervarTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Yaf_Request_Http
     */
    public $request=null;
    /**
     *
     * @var Yaf_Router
     */
    public $router=null;
    /**
     * Static route require access to the modules of the
     * application
     * @var Yaf_Application
     */
    public function setUp()
    {
        // request
        $this->request = new Yaf_Request_Http();
        $this->router = new Yaf_Router();
        $app = Yaf_Application::app();
        if ($app == null) {
            //Lets create the Application
            $app = new Yaf_Application("framework/Yaf/_files/application.ini");
        }
    }


    public function testSupervarSuccess()
    {
        if (defined('YAF_MODE')) {
            $this->markTestSkipped(
                'Yaf_Config_Simple does not allow number as key'
            );
            return;
        }
        $_GET['page'] = '/Foo/contr/action/rest/which/can/be/long';

        $route = new Yaf_Route_Supervar('page');
        $return = $route->route($this->request);

        $this->assertTrue($return);
        $this->assertEquals('Foo', $this->request->getModuleName());
        $this->assertEquals('contr', $this->request->getControllerName());
        $this->assertEquals('action', $this->request->getActionName());
        $params = $this->request->getParams();

        $this->assertTrue(is_array($params));
        $this->assertEquals(
            array(
                "rest"=>"which",
                "can"=>"be",
                "long"=>null,
            ), $params
        );

    }

    public function testSupervarFail()
    {
        if (defined('YAF_MODE')) {
            $this->markTestSkipped(
                'Yaf_Config_Simple does not allow number as key'
            );
            return;
        }
        $_GET['page'] = '';

        $route = new Yaf_Route_Supervar('page');
        $return = $route->route($this->request);

        $this->assertFalse($return);
        $this->assertEquals(null, $this->request->getModuleName());
        $this->assertEquals(null, $this->request->getControllerName());
        $this->assertEquals(null, $this->request->getActionName());
        $params = $this->request->getParams();

        $this->assertTrue(empty($params));

    }

    public function testSupervarNoValue()
    {
        if (defined('YAF_MODE')) {
            $this->markTestSkipped(
                'Yaf_Config_Simple does not allow number as key'
            );
            return;
        }
        $_GET['page'] = '/';

        $route = new Yaf_Route_Supervar('page');
        $return = $route->route($this->request);

        $this->assertTrue($return);
        $this->assertEquals(null, $this->request->getModuleName());
        $this->assertEquals(null, $this->request->getControllerName());
        $this->assertEquals(null, $this->request->getActionName());
        $params = $this->request->getParams();

        $this->assertTrue(empty($params));

    }



    public function testSupervarOneParam()
    {
        //echo ini_get('yaf.action_prefer');
        //echo getenv('action_prefer');
        $_GET['page'] = '/Foo/';

        $route = new Yaf_Route_Supervar('page');
        $return = $route->route($this->request);

        $this->assertTrue($return, 'The return of routing');
        $this->assertEquals(
            'Foo',
            $this->request->getControllerName(),
            'The request paramt is set to controller'
        );
        $params = $this->request->getParams();
        $this->assertTrue(empty($params));

    }

    public function testSupervarTwoParam()
    {
        //echo ini_get('yaf.action_prefer');
        //echo getenv('action_prefer');
        $_GET['page'] = '/Foo/Name/';

        $route = new Yaf_Route_Supervar('page');
        $return = $route->route($this->request);

        $this->assertTrue($return, 'The return of routing');
        $this->assertEquals(
            'Foo',
            $this->request->getControllerName(),
            'The request param is set to controller'
        );
        $this->assertEquals(
            'Name',
            $this->request->getActionName(),
            'The request param is set to action'
        );
        $params = $this->request->getParams();
        $this->assertTrue(empty($params));

    }

    public function testSupervarThreeParamValidModule()
    {
        //echo ini_get('yaf.action_prefer');
        //echo getenv('action_prefer');
        $_GET['page'] = '/Foo/Bar/Name/';


        $route = new Yaf_Route_Supervar('page');
        $return = $route->route($this->request);

        $this->assertTrue($return, 'The return of routing');
        $this->assertEquals(
            'Foo',
            $this->request->getModuleName(),
            'The request param is set to module'
        );
        $this->assertEquals(
            'Bar',
            $this->request->getControllerName(),
            'The request param is set to controller'
        );
        $this->assertEquals(
            'Name',
            $this->request->getActionName(),
            'The request param is set to action'
        );
        $params = $this->request->getParams();
        $this->assertTrue(empty($params));

    }

    public function testSupervarThreeParamInvalidModule()
    {
        //echo ini_get('yaf.action_prefer');
        //echo getenv('action_prefer');
        $_GET['page'] = '/FooN/Bar/Name/';

        $route = new Yaf_Route_Supervar('page');
        $return = $route->route($this->request);

        $this->assertTrue($return, 'The return of routing');
        $this->assertEquals(
            null,
            $this->request->getModuleName(),
            'Module should not be set'
        );
        $this->assertEquals(
            'FooN',
            $this->request->getControllerName(),
            'The request param is set to controller'
        );
        $this->assertEquals(
            'Bar',
            $this->request->getActionName(),
            'The request param is set to action'
        );
        $params = $this->request->getParams();
        $this->assertFalse(empty($params));
        $this->assertEquals(
            array(
                "Name"=>null,
            ), $params
        );

    }

    public function testSupervarFourParamValidModule()
    {
        //echo ini_get('yaf.action_prefer');
        //echo getenv('action_prefer');
        $_GET['page'] = '/Foo/Bar/Name/Value';

        $route = new Yaf_Route_Supervar('page');
        $return = $route->route($this->request);

        $this->assertTrue($return, 'The return of routing');
        $this->assertEquals(
            'Foo',
            $this->request->getModuleName(),
            'The request param is set to module'
        );
        $this->assertEquals(
            'Bar',
            $this->request->getControllerName(),
            'The request param is set to controller'
        );
        $this->assertEquals(
            'Name',
            $this->request->getActionName(),
            'The request param is set to action'
        );
        $params = $this->request->getParams();
        $this->assertFalse(empty($params));
        $this->assertEquals(
            array(
                "Value"=>null,
            ), $params
        );

    }

    public function testSupervarFourParamInvalidModule()
    {
        $_GET['page'] = '/FooN/Bar/Name/Value';

        $route = new Yaf_Route_Supervar('page');
        $return = $route->route($this->request);


        $this->assertTrue($return, 'The return of routing');
        $this->assertEquals(
            null,
            $this->request->getModuleName(),
            'The request param is set to module'
        );
        $this->assertEquals(
            'FooN',
            $this->request->getControllerName(),
            'The request param is set to controller'
        );
        $this->assertEquals(
            'Bar',
            $this->request->getActionName(),
            'The request param is set to action'
        );
        $params = $this->request->getParams();
        $this->assertFalse(empty($params));
        $this->assertEquals(
            array(
                "Name"=>'Value',
            ), $params
        );

    }

    public function testSupervarFiveParamValidModule()
    {
        //echo ini_get('yaf.action_prefer');
        //echo getenv('action_prefer');
        $_GET['page'] = '/Foo/Bar/Name/Value/Name1';

        $route = new Yaf_Route_Supervar('page');
        $return = $route->route($this->request);

        $this->assertTrue($return, 'The return of routing');
        $this->assertEquals(
            'Foo',
            $this->request->getModuleName(),
            'The request param is set to module'
        );
        $this->assertEquals(
            'Bar',
            $this->request->getControllerName(),
            'The request param is set to controller'
        );
        $this->assertEquals(
            'Name',
            $this->request->getActionName(),
            'The request param is set to action'
        );
        $params = $this->request->getParams();
        $this->assertFalse(empty($params));
        $this->assertEquals(
            array(
                "Value"=>'Name1',
            ), $params
        );

    }

    public function testSupervarFiveParamInvalidModule()
    {
        //echo ini_get('yaf.action_prefer');
        //echo getenv('action_prefer');
        $_GET['page'] = '/FooN/Bar/Name/Value/Name1';

        $route = new Yaf_Route_Supervar('page');
        $return = $route->route($this->request);

        $this->assertTrue($return, 'The return of routing');
        $this->assertEquals(
            null,
            $this->request->getModuleName(),
            'The request param is set to module'
        );
        $this->assertEquals(
            'FooN',
            $this->request->getControllerName(),
            'The request param is set to controller'
        );
        $this->assertEquals(
            'Bar',
            $this->request->getActionName(),
            'The request param is set to action'
        );
        $params = $this->request->getParams();
        $this->assertFalse(empty($params));
        $this->assertEquals(
            array(
                "Name"=>'Value',
                "Name1"=>null,
            ), $params
        );

    }

    public function testSupervarManyParamValidModule()
    {
        //echo ini_get('yaf.action_prefer');
        //echo getenv('action_prefer');
        $_GET['page'] = '/Foo/Bar/Name/Value/Name1/Value1/Name2/Value2';

        $route = new Yaf_Route_Supervar('page');
        $return = $route->route($this->request);

        $this->assertTrue($return, 'The return of routing');
        $this->assertEquals(
            'Foo',
            $this->request->getModuleName(),
            'The request param is set to module'
        );
        $this->assertEquals(
            'Bar',
            $this->request->getControllerName(),
            'The request param is set to controller'
        );
        $this->assertEquals(
            'Name',
            $this->request->getActionName(),
            'The request param is set to action'
        );
        $params = $this->request->getParams();
        $this->assertFalse(empty($params));
        $this->assertEquals(
            array(
                "Value"=>'Name1',
                "Value1"=>'Name2',
                "Value2"=>null,
            ), $params
        );

    }

    public function testSupervarManyParamInvalidModule()
    {
        //echo ini_get('yaf.action_prefer');
        //echo getenv('action_prefer');
        $_GET['page'] = '/FooN/Bar/Name/Value/Name1/Value1/Name2/Value2';

        $route = new Yaf_Route_Supervar('page');
        $return = $route->route($this->request);

        $this->assertTrue($return, 'The return of routing');
        $this->assertEquals(
            null,
            $this->request->getModuleName(),
            'The request param is set to module'
        );
        $this->assertEquals(
            'FooN',
            $this->request->getControllerName(),
            'The request param is set to controller'
        );
        $this->assertEquals(
            'Bar',
            $this->request->getActionName(),
            'The request param is set to action'
        );
        $params = $this->request->getParams();
        $this->assertFalse(empty($params));
        $this->assertEquals(
            array(
                "Name"=>'Value',
                "Name1"=>'Value1',
                "Name2"=>'Value2',
            ), $params
        );

    }
    public function tearDown()
    {
        $this->request=null;
        $this->router=null;
    }
}
