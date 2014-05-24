<?php
/**
 * @group Yaf
 * @group YafRoute
 * @group YafRouteMap
 */
class YafRouteMapTest extends PHPUnit_Framework_TestCase
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
    }


    public function testMapNoDelimiterInRequestControllerPrefer()
    {
        $route = new Yaf_Route_Map(true);
        $this->request->setRequestUri('/product/foo/bar/');
        $this->request->setBaseUri('/');
        $return = $route->route($this->request);
        $this->assertTrue($return);

        $this->assertEquals(null, $this->request->getModuleName());
        $this->assertEquals(
            'product_foo_bar', $this->request->getControllerName()
        );
        $this->assertEquals(null, $this->request->getActionName());
        $params = $this->request->getParams();
        $this->assertEquals(array(), $params);
    }

    public function testMapNoDelimiterInRequestActionPrefer()
    {
        $route = new Yaf_Route_Map();
        $this->request->setRequestUri('/product/foo/bar/');
        $this->request->setBaseUri('/');
        $return = $route->route($this->request);
        $this->assertTrue($return);

        $this->assertEquals(null, $this->request->getModuleName());
        $this->assertEquals(
            'product_foo_bar', $this->request->getActionName()
        );
        $this->assertEquals(null, $this->request->getControllerName());
        $params = $this->request->getParams();
        $this->assertEquals(array(), $params);
    }

    public function testMapDelimiterInRequest()
    {
        $route = new Yaf_Route_Map(true, '_');
        $this->request->setRequestUri('/user/list/_/foo/22');
        $this->request->setBaseUri('/');
        $return = $route->route($this->request);
        $this->assertTrue($return);

        $this->assertEquals(null, $this->request->getModuleName());
        $this->assertEquals(
            'user_list', $this->request->getControllerName()
        );
        $this->assertEquals(null, $this->request->getActionName());
        $params = $this->request->getParams();
        $this->assertEquals(array('foo'=>'22'), $params);
    }

    public function tearDown()
    {
        $this->request=null;
        $this->router=null;
    }
}
