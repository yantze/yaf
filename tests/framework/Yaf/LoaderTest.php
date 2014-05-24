<?php
/**
 * @group Yaf
 * @group YafLoader
 */
class YafLoaderTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Yaf_Loader
     */
    public $loader=null;

    public function setUpLoader()
    {
        // loader
        $this->loader = Yaf_Loader::getInstance(
            TEST_APPLICATION_PATH."_files",
            TEST_APPLICATION_PATH."_files/global"
        );
    }

    public function testNamespace()
    {
        ini_set("yaf.lowcase_path", false);
        $this->setUpLoader();
        try {
            $this->loader->registerLocalNamespace(null);
        } catch (Exception $expected) {
            $this->assertContains(
                'Invalid parameters provided, must be a string, or an array',
                $expected->getMessage()
            );
        }
        $this->loader->registerLocalNamespace("Baidu");
        $this->assertEquals(':Baidu:', $this->loader->getLocalNamespace());
        $this->loader->clearLocalNamespace();
        $this->assertEquals('', $this->loader->getLocalNamespace());
        $this->loader->registerLocalNamespace(array("Baidu", "Test"));
        $this->assertEquals(':Baidu:Test:', $this->loader->getLocalNamespace());


        //$this->assertTrue($this->loader->isLocalName("Baidu_Name"));

    }

    public function testCase03()
    {
        $this->setUpLoader();
        $this->loader->clearLocalNamespace();
        $this->loader->registerLocalNamespace("Baidu");
        $this->assertTrue($this->loader->isLocalName('Baidu_Name'));
        if (!defined('YAF_MODE')) {
            Yaf_G::iniSet('yaf.use_spl_autoload', false);
        } else {
            ini_set('yaf.use_spl_autoload', false);
        }
        try {
            $this->loader->autoload("Baidu_Name");
        } catch (PHPUnit_Framework_Error_Warning $e) {
            $this->assertContains(
                'Could not find script '.TEST_APPLICATION_PATH.
                '_files/Baidu/Name.php',
                $e->getMessage()
            );
        }
        try {
            $this->loader->autoload("Global_Name");
        } catch (PHPUnit_Framework_Error_Warning $e) {
            $this->assertContains(
                'Could not find script '.TEST_APPLICATION_PATH.
                '_files/global/Global/Name.php',
                $e->getMessage()
            );
        }
    }

    /**
     * @runInSeparateProcess
     */
    public function testCase023()
    {
        if (!defined('YAF_MODE')) {
            Yaf_G::iniSet('yaf.use_spl_autoload', false);
            Yaf_G::iniSet('yaf.lowcase_path', false);
        } else {
            ini_set('yaf.use_spl_autoload', false);
            ini_set('yaf.lowcase_path', false);
        }
        $this->setUpLoader();
        $this->loader->registerLocalNamespace(array('Foo'));
        try {
            $this->loader->autoload('Foo_Bar');
        } catch (PHPUnit_Framework_Error_Warning $e) {
            $this->assertContains(
                'Could not find script '.TEST_APPLICATION_PATH.
                '_files/Foo/Bar.php',
                $e->getMessage()
            );
        }
        try {
            $this->loader->autoload('Bar_Foo');
        } catch (PHPUnit_Framework_Error_Warning $e) {
            $this->assertContains(
                'Could not find script '.TEST_APPLICATION_PATH.
                '_files/global/Bar/Foo.php',
                $e->getMessage()
            );
        }
        $this->loader->setLibraryPath('/foobar', false);
        try {
            $this->loader->autoload('Foo_Bar');
        } catch (PHPUnit_Framework_Error_Warning $e) {
            $this->assertContains(
                'Could not find script /foobar/Foo/Bar.php',
                $e->getMessage()
            );
        }
        try {
            $this->loader->autoload('Bar_Foo');
        } catch (PHPUnit_Framework_Error_Warning $e) {
            $this->assertContains(
                'Could not find script '.TEST_APPLICATION_PATH.
                '_files/global/Bar/Foo.php',
                $e->getMessage()
            );
        }
        $this->loader->setLibraryPath('/foobar', true);
        try {
            $this->loader->autoload('Foo_Bar');
        } catch (PHPUnit_Framework_Error_Warning $e) {
            $this->assertContains(
                'Could not find script /foobar/Foo/Bar.php',
                $e->getMessage()
            );
        }
        try {
            $this->loader->autoload('Bar_Foo');
        } catch (PHPUnit_Framework_Error_Warning $e) {
            $this->assertContains(
                'Could not find script /foobar/Bar/Foo.php',
                $e->getMessage()
            );
        }
        try {
            $this->loader->autoload('Bar_Model');
        } catch (PHPUnit_Framework_Error_Warning $e) {
            $this->assertContains(
                'Couldn\'t load a framework MVC class without '.
                'an Yaf_Application initializing',
                $e->getMessage()
            );
        }
    }
    /**
     * @runInSeparateProcess
     */
    public function testCase024()
    {
        $globalDir = '/php/global/dir';
        if (!defined('YAF_MODE')) {
            Yaf_G::iniSet('yaf.library', $globalDir);
        } else {
            ini_set('yaf.library', $globalDir);
        }
        $this->setUpLoader();
        $this->assertEquals(
            TEST_APPLICATION_PATH."_files", $this->loader->getLibraryPath()
        );
        $this->assertEquals(
            TEST_APPLICATION_PATH."_files/global",
            $this->loader->getLibraryPath(true)
        );
        $config = array(
            "application" => array(
                "directory" => TEST_APPLICATION_PATH.'application/'
            )
        );
        $app = new Yaf_Application($config);
        $this->assertEquals(
            TEST_APPLICATION_PATH."application/library",
            $this->loader->getLibraryPath()
        );
        $this->assertEquals(
            $globalDir,
            $this->loader->getLibraryPath(true)
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function testCase025()
    {
        $globalDir = '/php/global/dir';
        if (!defined('YAF_MODE')) {
            Yaf_G::iniSet('yaf.library', $globalDir);
        } else {
            ini_set('yaf.library', $globalDir);
        }
        $config = array(
            "application" => array(
                "directory" => TEST_APPLICATION_PATH.'application/',
                "library" => array(
                    "directory" => "/tmp",
                    "namespace" => "Foo, Bar"
                )
            )
        );
        $app = new Yaf_Application($config);
        $this->loader = Yaf_Loader::getInstance();
        $this->loader->registerLocalNamespace("Dummy");
        $this->assertEquals(
            $config['application']['library']['directory'],
            $this->loader->getLibraryPath()
        );
        $this->assertEquals(
            $globalDir,
            $this->loader->getLibraryPath(true)
        );
        $this->assertEquals(
            ':Foo::Bar:Dummy:',
            $this->loader->getLocalNamespace()
        );
        $this->assertTrue($this->loader->isLocalName('Bar_Name'));
    }

    /**
     * @runaInSeparateProcess
     */
    public function testCase027()
    {
        $globalDir = '/php/global/dir';
        if (!defined('YAF_MODE')) {
            Yaf_G::iniSet('yaf.library', $globalDir);
            Yaf_G::iniSet('yaf.use_spl_autoload', false);
        } else {
            ini_set('yaf.library', $globalDir);
            ini_set('yaf.use_spl_autoload', false);
        }
        $config = array(
            "application" => array(
                "directory" => TEST_APPLICATION_PATH.'application/',
                "dispatcher" => array(
                    "catchException" => 0,
                    "throwException" => 0
                )
            )
        );
        include(dirname(__FILE__).'/Loader/testCase027.php');
        $app = new Yaf_Application($config);
        try {
            $app->execute('testCase027');
        } catch (PHPUnit_Framework_Error_Warning $e) {
            $this->assertContains(
                'Could not find script '.TEST_APPLICATION_PATH.
                'application/controllers/NoExists.php',
                $e->getMessage()
            );
        }
    }
    /**
     * @runInSeparateProcess
     */
    public function testCase037()
    {
        //ini_set('open_basedir', '.');
        //unfortunately setting open_basedir will make
        //phpunit to not work
        $globalDir = '/tmp/';
        if (!defined('YAF_MODE')) {
            Yaf_G::iniSet('yaf.library', $globalDir);
            Yaf_G::iniSet('yaf.lowcase_path', false);
        } else {
            ini_set('yaf.library', $globalDir);
            ini_set('yaf.lowcase_path', false);
        }
        $this->loader = Yaf_Loader::getInstance('/tmp');
        $this->loader->import("/tmp/1.php");
        try {
            $this->loader->autoload("Foo_Bar");
        } catch (PHPUnit_Framework_Error_Warning $e) {
            $this->assertContains(
                'Could not find script /tmp/Foo/Bar.php',
                $e->getMessage()
            );
        }
    }
}
