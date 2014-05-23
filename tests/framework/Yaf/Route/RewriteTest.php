<?php
/**
 * @group Yaf
 * @group YafRoute
 * @group YafRouteRewrite
 */
class YafRouteRewriteTest extends PHPUnit_Framework_TestCase
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


    public function testRewriteSuccess()
    {
        $route = new Yaf_Route_Rewrite(
            "/page/:id",
            array(
                "controller" => "page",
                "action" => "edit"
            )
        );
        //print "<pre>";print_r($route); print "</pre>";
        $this->request->setRequestUri('/page/12/');
        //$this->request->setBaseUri('/');
        $return = $route->route($this->request);
        //print "<pre>";print_r($this->request); print "</pre>";
        $this->assertTrue($return);

        $this->assertEquals(null, $this->request->getModuleName());
        $this->assertEquals('page', $this->request->getControllerName());
        $this->assertEquals('edit', $this->request->getActionName());
        $params = $this->request->getParams();
        $this->assertEquals(1, count($params));
        $this->assertEquals(
            array(
                "id"=>"12",
            ), $params
        );
    }


    public function testNotMatchedWithVariablesAndDefaults()
    {
        $route = new Yaf_Route_Rewrite(
            ':controller/:action',
            array(
                'controller' => 'index',
                'action' => 'index'
            )
        );
        $this->request->setRequestUri('archive/action/bogus');
        $return = $route->route($this->request);
        $this->assertFalse($return);
    }

    public function testNotMatchedWithVariablesAndStatic()
    {
        $route = new Yaf_Route_Rewrite('archive/:year/:month', array());
        $this->request->setRequestUri('ctrl/act/2000');
        $return = $route->route($this->request);
        $this->assertFalse($return);
    }

    public function testStaticMatchWithWildcard()
    {
        $route = new Yaf_Route_Rewrite(
            'news/view/*',
            array('controller' => 'news', 'action' => 'view')
        );
        $this->request->setRequestUri('/news/view/show/all/year/2000/empty/');
        $return = $route->route($this->request);
        $this->assertTrue($return);
        $this->assertEquals('news', $this->request->getControllerName());
        $this->assertEquals('view', $this->request->getActionName());
        $params = $this->request->getParams();
        $this->assertEquals(3, count($params));
        $this->assertEquals(
            array(
                "show"=>"all",
                "year"=>"2000",
                "empty"=>null,
            ), $params
        );
    }

    public function testVariableValues()
    {
        $route = new Yaf_Route_Rewrite(':controller/:action/:year', array());
        $this->request->setRequestUri('/ctrl/act/2000');
        $return = $route->route($this->request);
        $this->assertTrue($return);
        $this->assertEquals(null, $this->request->getControllerName());
        $this->assertEquals(null, $this->request->getActionName());
        $params = $this->request->getParams();
        $this->assertEquals(3, count($params));
        $this->assertEquals(
            array(
                "controller"=>"ctrl",
                "action"=>"act",
                "year"=>"2000",
            ), $params
        );

    }

    public function testRewriteInstanceArray()
    {
        $this->request->setRequestUri('/product/test/14');
        $this->router->addConfig(
            array(
                'route_name1'=>array(
                    'type' => 'rewrite',
                    'match' => '/product/:name/:value',
                    'route' => array(
                            'controller' => 'product',
                            'action' => 'info',
                        ),
                )
            )
        );
        $return = $this->router->route($this->request);
        $currentRouteName = $this->router->getCurrentRoute();
        $this->assertEquals('route_name1', $currentRouteName);
        $currentRoute = $this->router->getRoute($currentRouteName);
        $this->assertInstanceOf('Yaf_Route_Rewrite', $currentRoute);
        $this->assertEquals(null, $this->request->getModuleName());
        $this->assertEquals('product', $this->request->getControllerName());
        $this->assertEquals('info', $this->request->getActionName());
        $params = $this->request->getParams();

        $this->assertTrue(is_array($params));
        $this->assertEquals(
            array(
                "name"=>"test",
                "value"=>"14",
            ), $params
        );
    }
    public function testRewriteInstanceConfig()
    {
        $config = new Yaf_Config_Ini("framework/Yaf/_files/route_rewrite.ini");
        $this->router->addConfig($config->production->routes);
        $this->request->setRequestUri('/product/test/14');
        $return = $this->router->route($this->request);
        $currentRouteName = $this->router->getCurrentRoute();
        $this->assertEquals('route_name1', $currentRouteName);
        $currentRoute = $this->router->getRoute($currentRouteName);
        $this->assertInstanceOf('Yaf_Route_Rewrite', $currentRoute);
        $this->assertEquals(null, $this->request->getModuleName());
        $this->assertEquals('product', $this->request->getControllerName());
        $this->assertEquals('info', $this->request->getActionName());
        $params = $this->request->getParams();

        $this->assertTrue(is_array($params));
        $this->assertEquals(
            array(
                "name"=>"test",
                "value"=>"14",
            ), $params
        );
    }

    public function testCase011()
    {
        $this->request->setRequestUri('/subdir/ap/1.2/name/value');
        $this->request->setBaseUri('/subdir');
        $route = new Yaf_Route_Rewrite(
            "/subdir/:name/:version",
            array(
                "action" => "version"
            )
        );
        $this->router->addRoute("subdir", $route)
            ->addRoute(
                "ap",
                new Yaf_Route_Rewrite(
                    "/ap/:version/*",
                    array(
                        "action" => 'ap'
                    )
                )
            )->route($this->request);

        $this->assertEquals('ap', $this->router->getCurrentRoute());
        $this->assertEquals('1.2', $this->request->getParam('version'));
        $this->assertEquals('ap', $this->request->getActionName());
        $this->assertEquals(null, $this->request->getControllerName());
        $this->assertEquals('value', $this->request->getParam('name'));

    }

    public function tearDown()
    {
        $this->request=null;
        $this->router=null;
    }
}
