<?php
/**
 * @group Yaf
 * @group YafRouter
 */
class YafRouterTest extends PHPUnit_Framework_TestCase
{
    public function testMapRouterViaConfig()
    {
        $router = new Yaf_Router();
        $config = array(
            "name" => array(
                "type" => "map",         //Yaf_Route_Map route
                "controllerPrefer" => false,
                "delimiter" => "#!"
            )
        );
        $router->addConfig(
            new Yaf_Config_Simple($config)
        );
        $request = new Yaf_Request_Http('/user/list/#!/foo/22', '/');
        $router->route($request);
        $this->assertEquals(null, $request->getModuleName());
        $this->assertEquals(
            'user_list', $request->getActionName()
        );
        $this->assertEquals(null, $request->getControllerName());
        $params = $request->getParams();
        $this->assertEquals(array('foo'=>'22'), $params);
    }

    /**
     * @runInSeparateProcess
     */
    public function testCase008()
    {
        $router = new Yaf_Router();
        $route  = new Yaf_Route_Simple('m', 'c', 'a');
        $sroute = new Yaf_Route_Supervar('r');
        $router->addRoute("simple", $route)->addRoute("super", $sroute);
        $routes = $router->getRoutes();
        $this->assertEquals(3, count($routes));
        $this->assertSame($route, $routes['simple']);
        $this->assertSame($sroute, $routes['super']);
        $this->assertNull($router->getCurrentRoute());
        $this->assertSame($route, $router->getRoute('simple'));
        $this->assertNull($router->getRoute('noexists'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testCase036()
    {
        $testData = array(
            0 => array(
                'url' => '/',
                'expected' => array(
                    'm' => null,
                    'c' => null,
                    'a' => null,
                    'args' => array()
                )
            ),
            1 => array(
                'url' => '/foo',
                'expected' => array(
                    'm' => null,
                    'c' => 'foo',
                    'a' => null,
                    'args' => array()
                )
            ),
            2 => array(
                'url' => '/foo/',
                'expected' => array(
                    'm' => null,
                    'c' => 'foo',
                    'a' => null,
                    'args' => array()
                )
            ),
            3 => array(
                'url' => '/foo///bar',
                'expected' => array(
                    'm' => null,
                    'c' => 'foo',
                    'a' => 'bar',
                    'args' => array()
                )
            ),
            4 => array(
                'url' => 'foo/bar',
                'expected' => array(
                    'm' => null,
                    'c' => 'foo',
                    'a' => 'bar',
                    'args' => array()
                )
            ),
            5 => array(
                'url' => '/foo/bar/',
                'expected' => array(
                    'm' => null,
                    'c' => 'foo',
                    'a' => 'bar',
                    'args' => array()
                )
            ),
            6 => array(
                'url' => '/foo/bar/dummy',
                'expected' => array(
                    'm' => null,
                    'c' => 'foo',
                    'a' => 'bar',
                    'args' => array('dummy'=>null)
                )
            ),
            7 => array(
                'url' => '/foo///bar/dummy',
                'expected' => array(
                    'm' => null,
                    'c' => 'foo',
                    'a' => 'bar',
                    'args' => array('dummy'=>null)
                )
            ),
            8 => array(
                'url' => 'foo/bar/dummy',
                'expected' => array(
                    'm' => null,
                    'c' => 'foo',
                    'a' => 'bar',
                    'args' => array('dummy'=>null)
                )
            ),
            9 => array(
                'url' => '/my',
                'expected' => array(
                    'm' => null,
                    'c' => 'my',
                    'a' => null,
                    'args' => array()
                )
            ),
            10 => array(
                'url' => '/my/',
                'expected' => array(
                    'm' => null,
                    'c' => 'my',
                    'a' => null,
                    'args' => array()
                )
            ),
            11 => array(
                'url' => '/my/foo',
                'expected' => array(
                    'm' => null,
                    'c' => 'my',
                    'a' => 'foo',
                    'args' => array()
                )
            ),
            12 => array(
                'url' => '/my/foo/',
                'expected' => array(
                    'm' => null,
                    'c' => 'my',
                    'a' => 'foo',
                    'args' => array()
                )
            ),
            13 => array(
                'url' => '/my/foo/bar',
                'expected' => array(
                    'm' => 'my',
                    'c' => 'foo',
                    'a' => 'bar',
                    'args' => array()
                )
            ),
            14 => array(
                'url' => '/my/foo/bar/',
                'expected' => array(
                    'm' => 'my',
                    'c' => 'foo',
                    'a' => 'bar',
                    'args' => array()
                )
            ),
            15 => array(
                'url' => '/my/foo/bar/dummy/1',
                'expected' => array(
                    'm' => 'my',
                    'c' => 'foo',
                    'a' => 'bar',
                    'args' => array('dummy'=>'1')
                )
            ),
            16 => array(
                'url' => 'my/foo/bar/dummy/1/a/2/////',
                'expected' => array(
                    'm' => 'my',
                    'c' => 'foo',
                    'a' => 'bar',
                    'args' => array('dummy'=>'1', 'a'=>'2')
                )
            ),
            17 => array(
                'url' => '/my/index/index',
                'expected' => array(
                    'm' => 'my',
                    'c' => 'index',
                    'a' => 'index',
                    'args' => array()
                )
            ),
            18 => array(
                'url' => '/my/index',
                'expected' => array(
                    'm' => null,
                    'c' => 'my',
                    'a' => 'index',
                    'args' => array()
                )
            ),
            19 => array(
                'url' => '/foo/index',
                'expected' => array(
                    'm' => null,
                    'c' => 'foo',
                    'a' => 'index',
                    'args' => array()
                )
            ),
            20 => array(
                'url' => 'index/foo',
                'expected' => array(
                    'm' => null,
                    'c' => 'index',
                    'a' => 'foo',
                    'args' => array()
                )
            )
        );

        $config = array(
            "application" => array(
                "directory" => '/tmp/',
                "modules"   => 'Index,My',
             ),
        );
        $app = new Yaf_Application($config);
        $route = Yaf_Dispatcher::getInstance()->getRouter();
        foreach ($testData as $index=>$test) {
           $req = new Yaf_Request_Http($test['url']);
           $route->route($req);
           $this->assertEquals(
               $test['expected']['m'], $req->getModuleName(),
               'Failed module test for url:'.$test['url']
           );
           $this->assertEquals(
               $test['expected']['c'], $req->getControllerName(),
               'Failed controller test for url:'.$test['url']
           );
           $this->assertEquals(
               $test['expected']['a'], $req->getActionName(),
               'Failed action test for url:'.$test['url']
           );
           $this->assertEquals(
               $test['expected']['args'], $req->getParams(),
               'Failed param test for url:'.$test['url']
           );
        }
        if (!defined('YAF_MODE')) {
            Yaf_G::iniSet('yaf.action_prefer', true);
        } else {
            ini_set('yaf.action_prefer', true);
        }
        $testDataActionPrefer = array(
            0 => array(
                'url' => '/',
                'expected' => array(
                    'm' => null,
                    'c' => null,
                    'a' => null,
                    'args' => array()
                )
            ),
            1 => array(
                'url' => '/foo',
                'expected' => array(
                    'm' => null,
                    'c' => null,
                    'a' => 'foo',
                    'args' => array()
                )
            ),
            2 => array(
                'url' => '/foo/',
                'expected' => array(
                    'm' => null,
                    'c' => null,
                    'a' => 'foo',
                    'args' => array()
                )
            ),
            3 => array(
                'url' => '/my',
                'expected' => array(
                    'm' => null,
                    'c' => 'my',
                    'a' => null,
                    'args' => array()
                )
            ),
            4 => array(
                'url' => '/my/',
                'expected' => array(
                    'm' => null,
                    'c' => 'my',
                    'a' => null,
                    'args' => array()
                )
            ),
            5 => array(
                'url' => '/my/foo',
                'expected' => array(
                    'm' => null,
                    'c' => 'my',
                    'a' => 'foo',
                    'args' => array()
                )
            ),
            6 => array(
                'url' => '/my//foo',
                'expected' => array(
                    'm' => null,
                    'c' => 'my',
                    'a' => 'foo',
                    'args' => array()
                )
            )
        );
        foreach ($testDataActionPrefer as $index=>$test) {
           $req = new Yaf_Request_Http($test['url']);
           $route->route($req);
           $this->assertEquals(
               $test['expected']['m'], $req->getModuleName(),
               'Failed module test for url:'.$test['url']
           );
           $this->assertEquals(
               $test['expected']['c'], $req->getControllerName(),
               'Failed controller test for url:'.$test['url']
           );
           $this->assertEquals(
               $test['expected']['a'], $req->getActionName(),
               'Failed action test for url:'.$test['url']
           );
           $this->assertEquals(
               $test['expected']['args'], $req->getParams(),
               'Failed param test for url:'.$test['url']
           );
        }
    }

    public function testCase013()
    {
        $file = dirname(__FILE__) . "/_files/testCase013.ini";

        $config = new Yaf_Config_Ini($file, 'extra');

        $routes = $config->routes;
        $this->assertEquals('regex', $routes->regex->type);
        $this->assertEquals('^/ap/(.*)', $routes->regex->match);
        $this->assertEquals(
            array(
                'controller' => 'Index',
                'action' => 'action'
            ), $routes->regex->route->toArray()
        );
        $this->assertEquals(
            array(
                'type' => 'simple',
                'controller' => 'c',
                'module' => 'm',
                'action' => 'a'
            ), $routes->simple->toArray()
        );
        $this->assertEquals(
            array(
                'type' => 'supervar',
                'varname' => 'c'
            ), $routes->supervar->toArray()
        );
        $this->assertEquals(
            array(
                'type' => 'rewrite',
                'match' => '/yaf/:name/:value',
                'route' => array(
                    'controller' => 'Index',
                    'action' => 'action'
                )
            ), $routes->rewrite->toArray()
        );

        $router = new Yaf_Router();
        $router->addConfig($routes);
        $routes = $router->getRoutes();

        $this->assertTrue(isset($routes['regex']));
        $this->assertInstanceOf('Yaf_Route_Regex', $routes['regex']);
        $this->assertTrue(isset($routes['simple']));
        $this->assertInstanceOf('Yaf_Route_Simple', $routes['simple']);
        $this->assertTrue(isset($routes['supervar']));
        $this->assertInstanceOf('Yaf_Route_Supervar', $routes['supervar']);
        $this->assertTrue(isset($routes['rewrite']));
        $this->assertInstanceOf('Yaf_Route_Rewrite', $routes['rewrite']);
    }

    public function testCase019()
    {
        $request = new Yaf_Request_Http("/subdir/ap/1.2/name/value", "/subdir");
        $router = new Yaf_Router();
        $router->addConfig(
            array(
                array(
                    "type" => "regex",
                    "match" => "#^/ap/([^/]*)/*#i",
                    "route" => array(
                        array(
                            "action" => 'ap'
                        )
                    ),
                    "map" => array(
                        1 => 'version'
                    )
                )
            )
        )->route($request);
        $this->assertEquals(0, $router->getCurrentRoute());
        $this->assertEquals('1.2', $request->getParam("version"));
    }

    public function testBug62702()
    {
        if (!defined('YAF_MODE')) {
            Yaf_G::iniSet('yaf.action_prefer', false);
        } else {
            ini_set('yaf.action_prefer', false);
        }
        $router = new Yaf_Route_Static();
        $request = new Yaf_Request_Http("/sample", "/sample");
        $router->route($request);
        $this->assertNull($request->getControllerName());
        if (defined('YAF_MODE')) {
            echo PHP_EOL.'Yaf this version does not support this test';
        } else {
            $request = new Yaf_Request_Http("/Sample/ABC", "/sample");
            $router->route($request);
            $this->assertEquals('ABC', $request->getControllerName());
        }
        $router = new Yaf_Route_Map(true);
        $request = new Yaf_Request_Http("/sample/A/B/C", "/sample");
        $router->route($request);
        $this->assertEquals('A_B_C', $request->getControllerName());
        $request = new Yaf_Request_Http("/sample", "/sAmplE");
        $router->route($request);
        $this->assertNull($request->getControllerName());
        $router = new Yaf_Route_Regex(
            "#^/test#", array("controller" => "info"), array()
        );
        $request = new Yaf_Request_Http("/test/", "/Test");
        $router->route($request);
        $this->assertNull($request->getControllerName());
        $request = new Yaf_Request_Http("/sample/test", "/sAmplE");
        $router->route($request);
        $this->assertEquals('info', $request->getControllerName());
        $router = new Yaf_Route_Rewrite(
            "/test", array("controller" => "info"), array()
        );
        $request = new Yaf_Request_Http("/test/", "/Test");
        $router->route($request);
        $this->assertNull($request->getControllerName());
        $request = new Yaf_Request_Http("/sample/test", "/sAmplE");
        $router->route($request);
        $this->assertEquals('info', $request->getControllerName());
    }
}