<?php
/**
 * @group Yaf
 * @group YafRoute
 * @group YafRouteRegex
 */
class YafRouteRegexTest extends PHPUnit_Framework_TestCase
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

    public function testRegexSuccess()
    {

        $route = new Yaf_Route_Regex(
            "(.*)",
            array(
                "module" => "Foo",
                "controller" => "contr",
                "action"     => "action",
            ),
            array()
        );
        $this->request->setRequestUri('/test/Foo/');
        $this->request->setBaseUri('/test/');
        $return = $route->route($this->request);

        $this->assertTrue($return);

        $this->assertEquals('Foo', $this->request->getModuleName());
        $this->assertEquals('contr', $this->request->getControllerName());
        $this->assertEquals('action', $this->request->getActionName());
        $params = $this->request->getParams();
        $this->assertTrue(empty($params));
    }

    public function testRegexFail()
    {
        $route = new Yaf_Route_Regex(
            '/user\/(.*)/i', array(), array(1 => 'user')
        );
        $this->request->setRequestUri('/users/laurance');
        $this->request->setBaseUri('/');
        $return = $route->route($this->request);

        $this->assertFalse($return);

        $this->assertEquals(null, $this->request->getModuleName());
        $this->assertEquals(null, $this->request->getControllerName());
        $this->assertEquals(null, $this->request->getActionName());
        $params = $this->request->getParams();

        $this->assertTrue(empty($params));
    }

    public function testRegexUser()
    {
        $route = new Yaf_Route_Regex(
            '/user\/(.*)/i', array(), array(1 => 'user')
        );
        $this->request->setRequestUri('/user/laurance');
        $this->request->setBaseUri('/');
        $return = $route->route($this->request);

        $this->assertTrue($return);

        $this->assertEquals(null, $this->request->getModuleName());
        $this->assertEquals(null, $this->request->getControllerName());
        $this->assertEquals(null, $this->request->getActionName());
        $params = $this->request->getParams();

        $this->assertTrue(is_array($params));
    }

    public function testRegexNoMatch()
    {
        $route = new Yaf_Route_Regex('/users\/a\/laurance/i', array(), array());
        $this->request->setRequestUri('/users/a/');
        $this->request->setBaseUri('/');
        $return = $route->route($this->request);
        $this->assertFalse($return);
    }

    public function testRegexInstanceArray()
    {
        $this->request->setRequestUri('/list/mname/mvalue');
        $this->request->setBaseUri('/');
        $this->router->addConfig(
            array(
                'route_name1'=>array(
                    'type' => 'regex',
                    'match' => '#^list/([^/]*)/([^/]*)#',
                    'route' => array(
                            'controller' => 'Index',
                            'action' => 'action',
                        ),
                    'map' => array(
                            '1' => 'name',
                            '2' => 'value',
                        ),
                )
            )
        );
        $return = $this->router->route($this->request);
        $currentRouteName = $this->router->getCurrentRoute();
        $this->assertEquals('route_name1', $currentRouteName);
        $currentRoute = $this->router->getRoute($currentRouteName);
        $this->assertInstanceOf('Yaf_Route_Regex', $currentRoute);
        $this->assertEquals(null, $this->request->getModuleName());
        $this->assertEquals('Index', $this->request->getControllerName());
        $this->assertEquals('action', $this->request->getActionName());
        $params = $this->request->getParams();

        $this->assertTrue(is_array($params));
        $this->assertEquals(
            array(
                "name"=>"mname",
                "value"=>"mvalue",
            ), $params
        );
    }

    public function testRegexInstanceConfig()
    {
        $config = new Yaf_Config_Ini("framework/Yaf/_files/application.ini");
        $this->router->addConfig($config->product->routes);
        $this->request->setRequestUri('/list/mname/mvalue');
        $this->request->setBaseUri('/');
        $return = $this->router->route($this->request);
        $currentRouteName = $this->router->getCurrentRoute();
        $this->assertEquals('route_name4', $currentRouteName);
        $currentRoute = $this->router->getRoute($currentRouteName);
        $this->assertInstanceOf('Yaf_Route_Regex', $currentRoute);
        $this->assertEquals(null, $this->request->getModuleName());
        $this->assertEquals('Index', $this->request->getControllerName());
        $this->assertEquals('action', $this->request->getActionName());
        $params = $this->request->getParams();

        $this->assertTrue(is_array($params));
        $this->assertEquals(
            array(
                "name"=>"mname",
                "value"=>"mvalue",
            ), $params
        );
    }

    public function testDoubleMatch()
    {
        $route = new Yaf_Route_Regex(
            '/users\/(user_(\d+).html)/i',
            array(),
            array(
                1=>'path',
                2=>'userid'
            )
        );
        $this->request->setRequestUri('/users/user_1354.html');
        $this->request->setBaseUri('/');
        $return = $route->route($this->request);
        $this->assertTrue($return);


        $this->assertEquals(null, $this->request->getModuleName());
        $this->assertEquals(null, $this->request->getControllerName());
        $this->assertEquals(null, $this->request->getActionName());
        $params = $this->request->getParams();

        $this->assertTrue(is_array($params));
        $this->assertEquals(2, count($params));
        $this->assertEquals(
            array(
                "path"=>"user_1354.html",
                "userid"=>"1354",
            ), $params
        );
    }

    public function testNegativeMatch()
    {
        $route = new Yaf_Route_Regex(
            '/((?!admin|moderator).+)/i',
            array(
                'module' => 'index',
                'controller' => 'index'
            ),
            array(1 => 'action')
        );
        $this->request->setRequestUri('/users');
        $this->request->setBaseUri('/');
        $return = $route->route($this->request);
        $this->assertTrue($return);
        $this->assertEquals('index', $this->request->getModuleName());
        $this->assertEquals('index', $this->request->getControllerName());
        $this->assertEquals(null, $this->request->getActionName());
        $params = $this->request->getParams();

        $this->assertTrue(is_array($params));
        $this->assertEquals(1, count($params));
        $this->assertEquals(
            array(
                "action"=>"users",
            ), $params
        );

    }

    public function testNumericDefault()
    {
        $route = new Yaf_Route_Regex(
            '/users\/?(.+)?/i', array(), array(1 => 'username')
        );
        $this->request->setRequestUri('/users/laurence');
        $return = $route->route($this->request);
        $this->assertTrue($return);
        $this->assertEquals(null, $this->request->getModuleName());
        $this->assertEquals(null, $this->request->getControllerName());
        $this->assertEquals(null, $this->request->getActionName());
        $params = $this->request->getParams();

        $this->assertTrue(is_array($params));
        $this->assertEquals(1, count($params));
        $this->assertEquals(
            array(
                "username"=>"laurence",
            ), $params
        );
    }
    public function testMappedVariableMatch()
    {
        $route = new Yaf_Route_Regex(
            '/users\/(.+)/', array(), array(1 => 'username')
        );

        $this->request->setRequestUri('/users/laurence');
        $return = $route->route($this->request);
        $this->assertTrue($return);
        $params = $this->request->getParams();
        $this->assertEquals(1, count($params));
        $this->assertEquals(
            array(
                "username"=>"laurence",
            ), $params
        );

    }

    public function testMappedVariableWithNamedSubpattern()
    {
        $route = new Yaf_Route_Regex(
            '/users\/(?P<name>.+)/i', array(), array(1 => 'username')
        );
        $this->request->setRequestUri('/users/laurence');
        $return = $route->route($this->request);
        $this->assertTrue($return);
        $params = $this->request->getParams();
        $this->assertEquals(2, count($params));
        $this->assertEquals(
            array(
                "name"=>"laurence",
                "username"=>"laurence",
            ), $params
        );
    }

    public function testOptionalVar()
    {
        $route = new Yaf_Route_Regex(
            '/users\/(\w+)\/?(?:p\/(\d+))?/i',
            array(),
            array(
                1 => 'username',
                2 => 'page'
            )
        );
        $this->request->setRequestUri('/users/laurence/p/1');
        $return = $route->route($this->request);
        $this->assertTrue($return);
        $params = $this->request->getParams();
        $this->assertEquals(2, count($params));
        $this->assertEquals(
            array(
                "username"=>"laurence",
                "page"=>"1",
            ), $params
        );
    }

    public function testEmptyOptionalVar()
    {
        $route = new Yaf_Route_Regex(
            '/users\/(\w+)\/?(?:p\/(\d+))?/',
            array(),
            array(
                1 => 'username',
                2 => 'page'
            )
        );
        $this->request->setRequestUri('/users/laurence/');
        $return = $route->route($this->request);
        $this->assertTrue($return);
        $params = $this->request->getParams();
        $this->assertEquals(1, count($params));
        $this->assertEquals(
            array(
                "username"=>"laurence",
            ), $params
        );
    }

    public function testCase012()
    {
        $this->request->setRequestUri('/subdir/ap/1.2/name/value');
        $this->request->setBaseUri('/subdir');
        $route = new Yaf_Route_regex(
            "#/subdir/(.*)#",
            array(
                "action" => "version"
            ),
            array()
        );
        $this->router->addRoute("subdir", $route)
            ->addRoute(
                "ap",
                new Yaf_Route_Regex(
                    "#^/ap/([^/]*)/*#i",
                    array(
                        "action" => 'ap'
                    ),
                    array(
                        1 => 'version'
                    )
                )
            )->route($this->request);
        $this->assertEquals('ap', $this->router->getCurrentRoute());
        $this->assertEquals('1.2', $this->request->getParam('version'));
        $this->assertEquals('ap', $this->request->getActionName());
        $this->assertEquals(null, $this->request->getControllerName());
        $this->assertEquals(null, $this->request->getParam('name'));

    }

    public function tearDown()
    {
        $this->request=null;
        $this->router=null;
    }
}
