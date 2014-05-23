<?php
/**
 * @group Yaf
 * @group YafRoute
 * @group YafRouteStatic
 * @runTestsInSeparateProcesses
 */
class YafRouteStaticTest extends PHPUnit_Framework_TestCase
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


    public function testStaticSuccess()
    {
        $this->request->setRequestUri(
            '/test/Foo/contr/action/rest/which/can/be/long'
        );
        $this->request->setBaseUri('/test/');

        $route = new Yaf_Route_Static();
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

    public function testStaticNoParam()
    {
        //echo ini_get('yaf.action_prefer');
        //echo getenv('action_prefer');
        $this->request->setRequestUri(
            'Test'
        );
        $this->request->setBaseUri('');

        $route = new Yaf_Route_Static();
        $return = $route->route($this->request);

        $this->assertTrue($return, 'The return of routing');
        if (
            getenv('action_prefer')=='1'
            ||  ini_get('yaf.action_prefer')=='1'
        ) {
            $this->assertEquals(
                null,
                $this->request->getControllerName(),
                'The request paramt is set to controller'
            );
            $this->assertEquals(
                'Test',
                $this->request->getActionName(),
                'The request paramt is set to controller'
            );
        } else {
            $this->assertEquals(
                'Test',
                $this->request->getControllerName(),
                'The request paramt is set to controller'
            );
            $this->assertEquals(
                null,
                $this->request->getActionName(),
                'The request paramt is set to controller'
            );
        }
        $params = $this->request->getParams();
        $this->assertTrue(empty($params));

    }

    public function testStaticOneParam()
    {
        //echo ini_get('yaf.action_prefer');
        //echo getenv('action_prefer');
        $this->request->setRequestUri(
            '/test/Foo/'
        );
        $this->request->setBaseUri('/test/');

        $route = new Yaf_Route_Static();
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

    public function testStaticTwoParam()
    {
        //echo ini_get('yaf.action_prefer');
        //echo getenv('action_prefer');
        $this->request->setRequestUri(
            '/test/Foo/Name/'
        );
        $this->request->setBaseUri('/test/');


        $route = new Yaf_Route_Static();
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

    public function testStaticThreeParamValidModule()
    {
        //echo ini_get('yaf.action_prefer');
        //echo getenv('action_prefer');
        $this->request->setRequestUri(
            '/test/Foo/Bar/Name/'
        );
        $this->request->setBaseUri('/test/');


        $route = new Yaf_Route_Static();
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

    public function testStaticThreeParamInvalidModule()
    {
        //echo ini_get('yaf.action_prefer');
        //echo getenv('action_prefer');
        $this->request->setRequestUri(
            '/test/FooN/Bar/Name/'
        );
        $this->request->setBaseUri('/test/');

        $route = new Yaf_Route_Static();
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

    public function testStaticFourParamValidModule()
    {
        //echo ini_get('yaf.action_prefer');
        //echo getenv('action_prefer');
        $this->request->setRequestUri(
            '/test/Foo/Bar/Name/Value'
        );
        $this->request->setBaseUri('/test/');

        $route = new Yaf_Route_Static();
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

    public function testStaticFourParamInvalidModule()
    {
        $this->request->setRequestUri(
            '/test/FooN/Bar/Name/Value'
        );
        $this->request->setBaseUri('/test/');

        $route = new Yaf_Route_Static();
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

    public function testStaticFiveParamValidModule()
    {
        //echo ini_get('yaf.action_prefer');
        //echo getenv('action_prefer');
        $this->request->setRequestUri(
            '/test/Foo/Bar/Name/Value/Name1'
        );
        $this->request->setBaseUri('/test/');

        $route = new Yaf_Route_Static();
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

    public function testStaticFiveParamInvalidModule()
    {
        //echo ini_get('yaf.action_prefer');
        //echo getenv('action_prefer');
        $this->request->setRequestUri(
            '/test/FooN/Bar/Name/Value/Name1'
        );
        $this->request->setBaseUri('/test/');

        $route = new Yaf_Route_Static();
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

    public function testStaticManyParamValidModule()
    {
        //echo ini_get('yaf.action_prefer');
        //echo getenv('action_prefer');
        $this->request->setRequestUri(
            '/test/Foo/Bar/Name/Value/Name1/Value1/Name2/Value2'
        );
        $this->request->setBaseUri('/test/');

        $route = new Yaf_Route_Static();
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

    public function testStaticManyParamInvalidModule()
    {
        //echo ini_get('yaf.action_prefer');
        //echo getenv('action_prefer');
        $this->request->setRequestUri(
            '/test/FooN/Bar/Name/Value/Name1/Value1/Name2/Value2'
        );
        $this->request->setBaseUri('/test/');

        $route = new Yaf_Route_Static();
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

    public function testCase06()
    {
        $this->request->setRequestUri(
            "/prefix/controller/action/name/laruence/age/28"
        );
        $this->request->setBaseUri("/prefix/");
        $route = new Yaf_Route_Static();
        $this->assertTrue($route->route($this->request));
        $this->assertEquals(
            null,
            $this->request->getModuleName(),
            'The request param is set to module'
        );
        $this->assertEquals(
            'controller',
            $this->request->getControllerName(),
            'The request param is set to controller'
        );
        $this->assertEquals(
            'action',
            $this->request->getActionName(),
            'The request param is set to action'
        );
        $params = $this->request->getParams();
        $this->assertEquals(
            array(
                "name"=>'laruence',
                "age"=>'28'
            ), $params
        );

    }

    public function tearDown()
    {
        $this->request=null;
        $this->router=null;
    }
}
