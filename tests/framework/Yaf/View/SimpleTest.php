<?php
/**
 * @group Yaf
 * @group YafView
 * @group YafViewSimple
 */
class YafViewSimpleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Yaf_View_Simple
     */
    public $view=null;
    public function setUp()
    {
        // view
        $this->view = new Yaf_View_Simple(
            TEST_APPLICATION_PATH.'application/views/'
        );
    }

    public function testConstruct()
    {
        $this->assertEquals(
            TEST_APPLICATION_PATH.'application/views/',
            $this->readAttribute($this->view, '_tpl_dir')
        );
    }

    public function testSetScriptPath()
    {
        $oldValue = $this->readAttribute($this->view, '_tpl_dir');
        //empty template dir should not change the template dir
        $this->view->setScriptPath('');
        $this->assertEquals(
            $oldValue,
            $this->readAttribute($this->view, '_tpl_dir')
        );
        //setting it to an inexistent folder, it works
        // as yaf does not check the folder here
        $this->view->setScriptPath(TEST_APPLICATION_PATH.'someinvalidpath/');
        $this->assertEquals(
            TEST_APPLICATION_PATH.'someinvalidpath/',
            $this->readAttribute($this->view, '_tpl_dir')
        );
    }

    public function testGetScriptPath()
    {
        $this->view->setScriptPath(TEST_APPLICATION_PATH.'someinvalidpath/');
        $this->assertEquals(
            TEST_APPLICATION_PATH.'someinvalidpath/',
            $this->view->getScriptPath()
        );
    }

    public function testAssign()
    {
        $this->view->assign('test1', 'testValue');
        $viewVars = $this->readAttribute($this->view, '_tpl_vars');
        $this->assertEquals('testValue', $viewVars['test1']);
    }

    public function testCase09()
    {
        $this->view->assign('a', 'b');
        $this->view->assign('b', 'a');
        $this->assertEquals('b', $this->view->get('a'));
        $this->assertEquals(array('a'=>'b', 'b'=>'a'), $this->view->get());
        $this->view->clear('b');
        $this->assertEquals(array('a'=>'b'), $this->view->get());
        $this->view->clear();
        $this->assertEquals(array(), $this->view->get());
    }
    /**
     * Bug segfault while call exit in a view template
     * @runInSeparateProcess
     */
    public function testCase028()
    {
        set_exit_overload(
            function() {
                return FALSE;
            }
        );
        $output = $this->view->render('test/exit.phtml');
        $this->assertEquals('okey', trim($output));
    }

    public function testCase029()
    {
        $variable = 'laruence';
        $this->view->assign('name', $variable);
        unset($variable);
        $this->assertEquals('laruence', $this->view->name);
        $this->assertEquals(
            120, strlen($this->view->render('test/testCase029.phtml'))
        );
        $this->assertNull($this->view->nonexists);
    }

    public function testCase034()
    {
        $tpl = '<?php
        echo $name, "\n";
        foreach($entry as $list) {
           echo "1. ", $list, "\n";
        }
        ?><?=$name?>';

        $this->view->assign("entry", array('a', 'b', 'c'));
        if (!defined('YAF_MODE')) {
            $res = $this->view->evaluate($tpl, array('name' => 'template'));
        } else {
            $res = $this->view->eval($tpl, array('name' => 'template'));
        }
        $this->assertEquals(
            'template'.PHP_EOL.'1. a'.PHP_EOL.'1. b'.
            PHP_EOL.'1. c'.PHP_EOL.'template',
            $res
        );
    }

    public function testCase035()
    {
        $this->markTestSkipped(
            'Short open tag overwrite is not implemented and you should '.
            'consider creating templates based on the php.ini setting or best '.
            'portable with short open tag off.'
        );
    }
    public function testCase038()
    {
        $output = exec('php '.dirname(__FILE__).'/errorinview.php 2>&1');
        $this->assertContains(
            "PHP Parse error:  syntax error, unexpected '{' in ",
            trim($output)
        );
    }

    public function testCase039()
    {
        $output = exec('php '.dirname(__FILE__).'/recursiveRender.php 2>&1');
        $this->assertContains(
            "PHP Parse error:  syntax error, unexpected '{' in ",
            trim($output)
        );
    }

    public function testCase040()
    {
        $output = $this->view->render(null);
        $this->assertFalse($output);
        $output = $this->view->render(0);
        $this->assertFalse($output);
        $output = $this->view->render(true);
        $this->assertFalse($output);
    }
    /**
     * @runInSeparateProcess
     */
    public function testCase033()
    {
        $config = array(
            "application" => array(
                "directory" => TEST_APPLICATION_PATH.'application/',
                "dispatcher" => array(
                    "catchException" => 0,
                    "throwException" => 1
                ),
                "modules" => "testview"
            )
        );
        include (dirname(__FILE__).'/testCase033.php');

        $app = new Yaf_Application($config);
        $request = new Yaf_Request_Http("/testview/controller/action");
        try {
            $app->getDispatcher()->returnResponse(false)->dispatch($request);
            $this->fail(
                'Should raise an exception that the view template '.
                'modules/Testview/views/controller/action.phtml '.
                'is not found'
            );
        } catch (Exception $e) {
            $this->assertEquals(
                'Unable to find template '.
                TEST_APPLICATION_PATH.'application/modules/Testview/views/'.
                'controller/action.phtml',
                $e->getMessage()
            );
        }


        $view = new Yaf_View_Simple(TEST_APPLICATION_PATH . 'no-exists');
        $app->getDispatcher()->setView($view);
        try {
            $app->getDispatcher()->returnResponse(false)->dispatch($request);
            $this->fail(
                'Should raise an exception that the view template '.
                'no-exists/controller/action.phtml '.
                'is not found'
            );
        } catch (Yaf_Exception $e) {
            $this->assertEquals(
                'Unable to find template '.
                TEST_APPLICATION_PATH.'no-exists/controller/action.phtml',
                $e->getMessage()
            );
        }

        $request = new Yaf_Request_Http("/testview/controller/index");
        try {
            $app->getDispatcher()->returnResponse(false)->dispatch($request);
            $this->fail(
                'Should raise an exception that the view template '.
                'no-exists/controller/dummy.phtml '.
                'is not found'
            );
        } catch (Yaf_Exception $e) {
            $this->assertEquals(
                'Unable to find template '.
                TEST_APPLICATION_PATH.'no-exists/controller/dummy.phtml',
                $e->getMessage()
            );
        }

    }

    /**
     * @runInSeparateProcess
     */
    public function testCase041()
    {
        $config = array(
            "application" => array(
                "directory" => TEST_APPLICATION_PATH.'application/',
                "dispatcher" => array(
                    "catchException" => 0,
                    "throwException" => 1
                ),
                "modules" => "module"
            )
        );
        include (dirname(__FILE__).'/testCase041.php');

        $app = new Yaf_Application($config);
        $request = new Yaf_Request_Http("/module/controller/action");

        try {
            $app->getDispatcher()->returnResponse(false)->dispatch($request);
            $this->fail(
                'Should raise an exception that the view template '.
                'modules/Testview/views/controller/action.phtml '.
                'is not found'
            );
        } catch (Exception $e) {
            $this->assertEquals(
                'Unable to find template '.
                TEST_APPLICATION_PATH.'application/modules/Module/views/'.
                'controller/action.phtml',
                $e->getMessage()
            );
        }

        $view = new Yaf_View_Simple(TEST_APPLICATION_PATH . 'no-exists');
        $app->getDispatcher()->setView($view);
        try {
            $app->getDispatcher()->returnResponse(false)->dispatch($request);
            $this->fail(
                'Should raise an exception that the view template '.
                'no-exists/controller/action.phtml '.
                'is not found'
            );
        } catch (Yaf_Exception $e) {
            $this->assertEquals(
                'Unable to find template '.
                TEST_APPLICATION_PATH.'no-exists/controller/action.phtml',
                $e->getMessage()
            );
        }

        $request = new Yaf_Request_Http("/module/controller/index");
        try {
            $app->getDispatcher()->returnResponse(false)->dispatch($request);
            $this->fail(
                'Should raise an exception that the view template '.
                'no-exists/controller/dummy.phtml '.
                'is not found'
            );
        } catch (Yaf_Exception $e) {
            $this->assertEquals(
                'Unable to find template '.
                TEST_APPLICATION_PATH.'no-exists/controller/dummy.phtml',
                $e->getMessage()
            );
        }
    }
}