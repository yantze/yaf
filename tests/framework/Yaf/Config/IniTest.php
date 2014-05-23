<?php
/**
* @group Yaf
* @group YafConfig
* @group YafConfigIni
*/
class YafConfigIniTest extends PHPUnit_Framework_TestCase
{
    protected $_iniFileConfig;
    protected $_iniFileAllSectionsConfig;
    protected $_iniFileCircularConfig;

    public function setUp()
    {
        $this->_iniFileConfig = dirname(__FILE__) . '/_files/config.ini';
        $this->_iniFileAllSectionsConfig =
            dirname(__FILE__) . '/_files/allsections.ini';
        $this->_iniFileCircularConfig =
            dirname(__FILE__) . '/_files/circular.ini';
        $this->_iniFileMultipleInheritanceConfig =
            dirname(__FILE__) . '/_files/multipleinheritance.ini';
        $this->_iniFileSeparatorConfig =
            dirname(__FILE__) . '/_files/separator.ini';
        $this->_nonReadableConfig =
            dirname(__FILE__) . '/_files/nonreadable.ini';
        $this->_iniFileNoSectionsConfig =
            dirname(__FILE__) . '/_files/nosections.ini';
        $this->_iniFileInvalid = dirname(__FILE__) . '/_files/invalid.ini';
    }

    public function testLoadSingleSection()
    {
        $config = new Yaf_Config_Ini($this->_iniFileConfig, 'all');

        $this->assertEquals('all', $config->hostname);
        $this->assertEquals('live', $config->db->name);
        $this->assertEquals('multi', $config->one->two->three);
        $this->assertNull($config->nonexistent); // property doesn't exist
    }

    public function testSectionInclude()
    {
        $config = new Yaf_Config_Ini($this->_iniFileConfig, 'staging');

        $this->assertEquals('', $config->debug); // only in staging
        $this->assertEquals('thisname', $config->name); // only in all
         // only in all (nested version)
        $this->assertEquals('username', $config->db->user);
        // inherited and overridden
        $this->assertEquals('staging', $config->hostname);
        // inherited and overridden
        $this->assertEquals('dbstaging', $config->db->name);
    }

    public function testTrueValues()
    {
        $config = new Yaf_Config_Ini($this->_iniFileConfig, 'debug');

        $this->assertInternalType('string', $config->debug);
        $this->assertEquals('1', $config->debug);
        $this->assertInternalType('string', $config->values->changed);
        $this->assertEquals('1', $config->values->changed);
    }

    public function testEmptyValues()
    {
        $config = new Yaf_Config_Ini($this->_iniFileConfig, 'debug');

        $this->assertInternalType('string', $config->special->no);
        $this->assertEquals('', $config->special->no);
        $this->assertInternalType('string', $config->special->null);
        $this->assertEquals('', $config->special->null);
        $this->assertInternalType('string', $config->special->false);
        $this->assertEquals('', $config->special->false);
    }

    public function testMultiDepthExtends()
    {
        $config = new Yaf_Config_Ini($this->_iniFileConfig, 'other_staging');
        // only in other_staging
        $this->assertEquals('otherStaging', $config->only_in);
        // 1 level down: only in staging
        $this->assertEquals('', $config->debug);
        // 2 levels down: only in all
        $this->assertEquals('thisname', $config->name);
        // 2 levels down: only in all (nested version)
        $this->assertEquals('username', $config->db->user);
        // inherited from two to one and overridden
        $this->assertEquals('staging', $config->hostname);
        // inherited from two to one and overridden
        $this->assertEquals('dbstaging', $config->db->name);
        // inherited from two to other_staging and overridden
        $this->assertEquals('anotherpwd', $config->db->pass);
    }

    public function testErrorNoExtendsSection()
    {
        try {
            $config = new Yaf_Config_Ini($this->_iniFileConfig, 'extendserror');
            if (!defined('YAF_MODE')) {
                $this->fail(
                    'An expected Yaf_Config_Exception has not been raised'
                );
            }
        } catch (Yaf_Config_Exception $expected) {
            $this->assertContains('cannot be found', $expected->getMessage());
        }
    }

    public function testInvalidKeys()
    {
        $sections = array(
            'leadingdot', 'onedot', 'twodots', 'threedots', 'trailingdot'
        );
        foreach ($sections as $section) {
            try {
                $config = new Yaf_Config_Ini($this->_iniFileConfig, $section);
                if (!defined('YAF_MODE')) {
                    $this->fail(
                        'An expected Yaf_Config_Exception has not been raised'
                    );
                }
            } catch (Yaf_Config_Exception $expected) {
                $this->assertContains('Invalid key', $expected->getMessage());
            }
        }
    }

    public function testZF426()
    {
        try {
            $config = new Yaf_Config_Ini($this->_iniFileConfig, 'zf426');
            if (!defined('YAF_MODE')) {
                $this->fail(
                    'An expected Yaf_Config_Exception has not been raised'
                );
            }
        } catch (Yaf_Config_Exception $expected) {
            $this->assertContains(
                'Cannot create sub-key for', $expected->getMessage()
            );
        }
    }

    public function testZF413_MultiSections()
    {
        if (defined('YAF_MODE')) {
            $this->markTestSkipped(
                'Yaf_Config_Ini does not allow multiple sections'
            );
            return;
        }
        $config = new Yaf_Config_Ini(
            $this->_iniFileAllSectionsConfig, array(
                'staging','other_staging'
            )
        );

        $this->assertEquals('otherStaging', $config->only_in);
        $this->assertEquals('staging', $config->hostname);
    }

    public function testZF413_AllSections()
    {
        $config = new Yaf_Config_Ini($this->_iniFileAllSectionsConfig);
        $this->assertEquals('otherStaging', $config->other_staging->only_in);
        $this->assertEquals('staging', $config->staging->hostname);
    }



    public function testErrorNoFile()
    {
        try {
            $config = new Yaf_Config_Ini('', '');
                $this->fail(
                    'An expected Exception has not been raised'
                );
        } catch (Exception $expected) {
            $this->assertContains(
                'Unable to find config file', $expected->getMessage()
            );
        }
    }

    public function testErrorMultipleExensions()
    {
        try {
            $config = new Yaf_Config_Ini(
                $this->_iniFileMultipleInheritanceConfig, 'multiinherit'
            );
            if (!defined('YAF_MODE')) {
                $this->fail(
                    'An expected Yaf_Config_Exception has not been raised'
                );
            }
        } catch (Yaf_Config_Exception $expected) {
            $this->assertContains(
                'may not extend multiple sections', $expected->getMessage()
            );
        }
    }

    public function testErrorNoSectionFound()
    {
        try {
            $config = new Yaf_Config_Ini(
                $this->_iniFileConfig, 'notthere'
            );
            if (!defined('YAF_MODE')) {
                $this->fail(
                    'An expected Yaf_Config_Exception has not been raised'
                );
            }
        } catch (Yaf_Exception $expected) {
            $this->assertContains('There is no', $expected->getMessage());
        }
    }

    public function testZF2508NoSections()
    {
        $config = new Yaf_Config_Ini($this->_iniFileNoSectionsConfig);

        $this->assertEquals('all', $config->hostname);
        $this->assertEquals('two', $config->one->two);
        $this->assertEquals('4', $config->one->three->four);
        $this->assertEquals('5', $config->one->three->five);
    }

    public function testZF2843NoSectionNoTree()
    {
        $filename = dirname(__FILE__) . '/_files/zf2843.ini';
        $config = new Yaf_Config_Ini($filename);

        $this->assertEquals('123', $config->abc);
        $this->assertEquals('jkl', $config->ghi);
    }

    public function testZF3196_InvalidIniFile()
    {
        try {
            $config = @new Yaf_Config_Ini($this->_iniFileInvalid);
            if (defined('YAF_MODE')) {
                $this->fail(
                    'An expected Yaf_Config_Exception has not been raised'
                );
            }
        } catch (Yaf_Config_Exception $expected) {
            $this->assertRegexp(
                '/(Error parsing|syntax error, unexpected|failed)/',
                $expected->getMessage()
            );
        } catch (Yaf_Exception $expected) {
            $this->assertRegexp(
                '/(Unable to find|Error parsing|'.
                'syntax error, unexpected|failed)/',
                $expected->getMessage()
            );
        }

    }


    public function testZF8159()
    {
        $config = new Yaf_Config_Ini(
            dirname(__FILE__) . '/_files/zf8159.ini'
        );

        $this->assertTrue(
            isset($config->second->user->login->elements->password)
        );

        $this->assertEquals(
            'password',
            $config->second->user->login->elements->password->type
        );
    }


    public function testArraysOfKeysCreatedUsingAttributesAndKeys()
    {
        if (defined('YAF_MODE')) {
            $this->markTestSkipped(
                'Yaf_Config_Ini does not allow number as key'
            );
            return;
        }
        $filename = dirname(__FILE__) . '/_files/zf5800.ini';
        $config = new Yaf_Config_Ini($filename, 'dev');
        $this->assertEquals(
            'nice.guy@company.com', $config->receiver->{0}->mail
        );
        $this->assertEquals('1', $config->receiver->{0}->html);
        $this->assertFalse($config->receiver->mail);
    }

    public function testPreservationOfIntegerKeys()
    {
        if (defined('YAF_MODE')) {
            $this->markTestSkipped(
                'Yaf_Config_Ini does not allow number as key'
            );
            return;
        }
        $filename = dirname(__FILE__) . '/_files/zf6508.ini';
        $config = new Yaf_Config_Ini($filename, 'all');
        $this->assertEquals(true, isset($config->{1002}));
    }

    public function testCase010()
    {
        $iniAsArray = array(
            'base' => array(
            'application' => array(
            'directory' => APPLICATION_PATH.'/applcation'
            ), 'name' => 'base',
            'array' => array(
                1 => '1', 'name' => 'name'
            ), 5 => '5',
            'routes' => array(

            'regex' => array(
                'type' => 'regex', 'match' => '^/ap/(.*)',
            'route' => array(
                'controller' => 'Index', 'action' => 'action'
            ),
            'map' => array(
                0 => 'name', 1 => 'name', 2 => 'value'
            )
            ),
            'simple' => array(
                'type' => 'simple', 'controller' => 'c', 'module' => 'm',
            'action' => 'a'
            ),
            'supervar' => array(
                'type' => 'supervar', 'varname' => 'c'
            ),
            'rewrite' => array(
                'type' => 'rewrite', 'match' => '/yaf/:name/:value',
            'route' => array(
                'controller' => 'Index', 'action' => 'action'
            )
            )
            )
            ),
            'extra' => array(

            'application' => array(

            'directory' => APPLICATION_PATH.'/applcation'
            ), 'name' => 'extra',
            'array' => array(
                1 => '1', 'name' => 'new_name', 2 => 'test'
            ), 5 => '5',
            'routes' => array(

            'regex' => array(
                'type' => 'regex', 'match' => '^/ap/(.*)',
            'route' => array(
                'controller' => 'Index', 'action' => 'action'
            ),
            'map' => array(
                0 => 'name', 1 => 'name', 2 => 'value'
            )
            ),
            'simple' => array(
                'type' => 'simple', 'controller' => 'c', 'module' => 'm',
            'action' => 'a'
            ),
            'supervar' => array(
                'type' => 'supervar', 'varname' => 'c'
            ),
            'rewrite' => array(
                'type' => 'rewrite', 'match' => '/yaf/:name/:value',
            'route' => array(
                'controller' => 'Index', 'action' => 'action'
            )
            )
            ), 'value' => '2'
            ),
            'product' => array(

            'application' => array(

            'directory' => APPLICATION_PATH.'/applcation'
            ), 'name' => 'extra',
            'array' => array(
                1 => '1', 'name' => 'new_name', 2 => 'test'
            ), 5 => '5',
            'routes' => array(

            'regex' => array(
                'type' => 'regex', 'match' => '^/ap/(.*)',
            'route' => array(
                'controller' => 'Index', 'action' => 'action'
            ),
            'map' => array(
                0 => 'name', 1 => 'name', 2 => 'value'
            )
            ),
            'simple' => array(
                'type' => 'simple', 'controller' => 'c', 'module' => 'm',
            'action' => 'a'
            ),
            'supervar' => array(
                'type' => 'supervar', 'varname' => 'c'
            ),
            'rewrite' => array(
                'type' => 'rewrite', 'match' => '/yaf/:name/:value',
            'route' => array(
                'controller' => 'Index', 'action' => 'action'
            )
            )
            ), 'value' => '2'
            ),
            'nocatch' => array(

            'application' => array(

            'directory' => APPLICATION_PATH.'/applcation',
            'dispatcher' => array(
                'throwException' => '', 'catchException' => '1'
            )
            ), 'name' => 'extra',
            'array' => array(
                1 => '1', 'name' => 'new_name', 2 => 'test'
            ), 5 => '5',
            'routes' => array(

            'regex' => array(
                'type' => 'regex', 'match' => '^/ap/(.*)',
            'route' => array(
                'controller' => 'Index', 'action' => 'action'
            ),
            'map' => array(
                0 => 'name', 1 => 'name', 2 => 'value'
            )
            ),
            'simple' => array(
                'type' => 'simple', 'controller' => 'c', 'module' => 'm',
            'action' => 'a'
            ),
            'supervar' => array(
                'type' => 'supervar', 'varname' => 'c'
            ),
            'rewrite' => array(
                'type' => 'rewrite', 'match' => '/yaf/:name/:age',
            'route' => array(
                'controller' => 'Index', 'action' => 'action'
            )
            )
            ), 'value' => '2'
            ),
            'envtest' => array(
                'env' => '', 'ini' => '', 'const' => 'FOO'
            )
        );
        $iniExtraArray = array (
          'application' =>
          array (
            'directory' => APPLICATION_PATH.'/applcation',
          ),
          'name' => 'extra',
          'array' =>
          array (
            1 => '1',
            'name' => 'new_name',
            2 => 'test',
          ),
          5 => '5',
          'routes' =>
          array (
            'regex' =>
            array (
              'type' => 'regex',
              'match' => '^/ap/(.*)',
              'route' =>
              array (
                'controller' => 'Index',
                'action' => 'action',
              ),
              'map' =>
              array (
                0 => 'name',
                1 => 'name',
                2 => 'value',
              ),
            ),
            'simple' =>
            array (
              'type' => 'simple',
              'controller' => 'c',
              'module' => 'm',
              'action' => 'a',
            ),
            'supervar' =>
            array (
              'type' => 'supervar',
              'varname' => 'c',
            ),
            'rewrite' =>
            array (
              'type' => 'rewrite',
              'match' => '/yaf/:name/:value',
              'route' =>
              array (
                'controller' => 'Index',
                'action' => 'action',
              ),
            ),
          ),
          'value' => '2',
        );

        $file = dirname(__FILE__) . '/_files/testCase010.ini';
        $config = new Yaf_Config_Ini($file);
        $this->assertEquals($iniAsArray, $config->toArray());
        $config = new Yaf_Config_Ini($file, "extra");
        //$m = var_export($config->toArray(), true);
        $this->assertEquals($iniExtraArray, $config->toArray());
        $config = new Yaf_Config_Ini($file);
        $config->longtime = 23424234324;
        $this->assertTrue($config->readonly());
        $configSections = array(
            'base',
            'extra',
            'product',
            'nocatch',
            'envtest'
        );
        $n=0;
        foreach ($config as $key=>$value) {
            $this->assertEquals($configSections[$n], $key);
            $n++;
        }
        if (!defined('YAF_MODE')) {
            $sick = new Yaf_Config_Ini(
                dirname(__FILE__) . '/_files/empty.ini'
            );
        } else {
            $sick = @new Yaf_Config_Ini();
        }
        $this->assertFalse($sick->__isset(1));
        $this->assertNull($sick->__get(1));
        $sick->total = 1;
        $this->assertEquals(0, count($sick));
    }

    public function testCase017()
    {
        $config = new Yaf_Config_Ini(dirname(__FILE__) . '/_files/empty.ini');
        $this->assertNull($config->get("\0"));
    }

    public function testCase018()
    {
        $config = new Yaf_Config_Ini(
            dirname(__FILE__) . '/_files/testCase018.ini', 'base'
        );
        $iniAsArray = array (
          'application' =>
          array (
            'directory' => APPLICATION_PATH.'/applcation',
          ),
          'name' => 'base',
          'array' =>
          array (
            1 => '1',
            'name' => 'name',
          ),
          5 => '5',
          'routes' =>
          array (
            'regex' =>
            array (
              'type' => 'regex',
              'match' => '^/ap/(.*)',
              'route' =>
              array (
                'controller' => 'Index',
                'action' => 'action',
              ),
              'map' =>
              array (
                0 => 'name',
                1 => 'name',
                2 => 'value',
              ),
            ),
            'simple' =>
            array (
              'type' => 'simple',
              'controller' => 'c',
              'module' => 'm',
              'action' => 'a',
            ),
            'supervar' =>
            array (
              'type' => 'supervar',
              'varname' => 'c',
            ),
            'rewrite' =>
            array (
              'type' => 'rewrite',
              'match' => '/yaf/:name/:value',
              'route' =>
              array (
                'controller' => 'Index',
                'action' => 'action',
              ),
            ),
          ),
        );
        $this->assertEquals($iniAsArray, $config->toArray());
    }

    public function testCase030()
    {
        try {
          $config = new Yaf_Config_Ini(
              dirname(__FILE__) . '/_files/testCase030.ini', "ex"
          );
          $this->fail('An exception about section not found was not raised');
        } catch (Exception $e) {
            $this->assertContains(
                "There is no section 'ex' in '".dirname(__FILE__) .
                "/_files/testCase030.ini'",
                $e->getMessage()
            );
        }
    }

    public function testCase032()
    {
        putenv("FOO=bar");
        define("FOO", "Dummy");
        $config = new Yaf_Config_Ini(
            dirname(__FILE__) . '/_files/testCase032.ini', "envtest"
        );
        $this->assertEquals($config->env, 'bar');
        $this->assertEquals($config->const, 'Dummy');
    }

}