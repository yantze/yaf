<?php
/**
 * @group Yaf
 * @group YafRoute
 * @group YafRouteSimple
 */
class YafRouteSimpleTest extends PHPUnit_Framework_TestCase
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


    public function testSimpleSuccess()
    {
        if (defined('YAF_MODE')) {
            $this->markTestSkipped(
                'Yaf_Route_Simple does not allow to change the get'
            );
            return;
        }
        $_GET['module'] = 'Foo';
        $_GET['controller'] = 'contr';
        $_GET['action'] = 'action';
        $route = new Yaf_Route_Simple("module", "controller", "action");
        $this->request->setRequestUri('/');
        $this->request->setBaseUri('/');
        $return = $route->route($this->request);

        $this->assertTrue($return);

        $this->assertEquals('Foo', $this->request->getModuleName());
        $this->assertEquals('contr', $this->request->getControllerName());
        $this->assertEquals('action', $this->request->getActionName());
        $params = $this->request->getParams();
        $this->assertTrue(empty($params));
    }

    public function testSimpleFail()
    {
        $route = new Yaf_Route_Simple("Foo", "contr", "action");
        $this->request->setRequestUri('/');
        $this->request->setBaseUri('/');
        $return = $route->route($this->request);

        $this->assertFalse($return);

        $this->assertEquals(null, $this->request->getModuleName());
        $this->assertEquals(null, $this->request->getControllerName());
        $this->assertEquals(null, $this->request->getActionName());
        $params = $this->request->getParams();
        $this->assertTrue(empty($params));
    }


    public function testSimpleInstanceArray()
    {
        if (defined('YAF_MODE')) {
            $this->markTestSkipped(
                'Yaf_Route_Simple does not allow to change the get'
            );
            return;
        }
        $_GET['module'] = 'Foo';
        $_GET['controller'] = 'contr';
        $_GET['action'] = 'action';
        $this->request->setRequestUri('/');
        $this->request->setBaseUri('/');
        $this->router->addConfig(
            array(
                'route_name1'=>array(
                    'type' => 'simple',
                    'module' => 'module',
                    'controller' => 'controller',
                    'action' => 'action',
                )
            )
        );
        $return = $this->router->route($this->request);
        $currentRouteName = $this->router->getCurrentRoute();
        $this->assertEquals('route_name1', $currentRouteName);
        $currentRoute = $this->router->getRoute($currentRouteName);
        $this->assertInstanceOf('Yaf_Route_Simple', $currentRoute);
        $this->assertEquals('Foo', $this->request->getModuleName());
        $this->assertEquals('contr', $this->request->getControllerName());
        $this->assertEquals('action', $this->request->getActionName());
        $params = $this->request->getParams();

        $this->assertTrue(empty($params));
    }

    public function testSimpleInstanceConfig()
    {
        if (defined('YAF_MODE')) {
            $this->markTestSkipped(
                'Yaf_Route_Simple does not allow to change the get'
            );
            return;
        }
        $_GET['module'] = 'Foo';
        $_GET['controller'] = 'contr';
        $_GET['action'] = 'action';
        $config = new Yaf_Config_Ini("framework/Yaf/_files/route_simple.ini");
        $this->router->addConfig($config->production->routes);


        $this->request->setRequestUri('/');
        $this->request->setBaseUri('/');

        $return = $this->router->route($this->request);
        $currentRouteName = $this->router->getCurrentRoute();
        $this->assertEquals('route_name1', $currentRouteName);
        $currentRoute = $this->router->getRoute($currentRouteName);
        $this->assertInstanceOf('Yaf_Route_Simple', $currentRoute);
        $this->assertEquals('Foo', $this->request->getModuleName());
        $this->assertEquals('contr', $this->request->getControllerName());
        $this->assertEquals('action', $this->request->getActionName());
        $params = $this->request->getParams();

        $this->assertTrue(empty($params));
    }

    /*

    public function testSimpleFail()
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

    public function testSimpleNoValue()
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


    public function testSimpleOneParam()
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

    public function testSimpleTwoParam()
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

    public function testSimpleThreeParamValidModule()
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

    public function testSimpleThreeParamInvalidModule()
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

    public function testSimpleFourParamValidModule()
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

    public function testSimpleFourParamInvalidModule()
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

    public function testSimpleFiveParamValidModule()
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

    public function testSimpleFiveParamInvalidModule()
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

    public function testSimpleManyParamValidModule()
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

    public function testSimpleManyParamInvalidModule()
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
    */
    public function tearDown()
    {
        $this->request=null;
        $this->router=null;
    }
}
