<?php
/**
* @group Yaf
* @group YafConfig
* @group YafConfigSimple
*/
class YafConfigSimpleTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        // Arrays representing common config configurations
        $this->_all = array(
            'hostname' => 'all',
            'name' => 'thisname',
            'db' => array(
                'host' => '127.0.0.1',
                'user' => 'username',
                'pass' => 'password',
                'name' => 'live'
                ),
            'one' => array(
                'two' => array(
                    'three' => 'multi'
                    )
                )
            );

        $this->_numericData = array(
             0 => 34,
             1 => 'test',
            );

        $this->_menuData = array(
            'button' => array(
                'b0' => array(
                    'L1' => 'button0-1',
                    'L2' => 'button0-2',
                    'L3' => 'button0-3'
                ),
                'b1' => array(
                    'L1' => 'button1-1',
                    'L2' => 'button1-2'
                ),
                'b2' => array(
                    'L1' => 'button2-1'
                    )
                )
            );

        $this->_leadingdot = array('.test' => 'dot-test');
        $this->_invalidkey = array(' ' => 'test', ''=>'test2');
    }


    public function testLoadSingleSection()
    {
        $config = new Yaf_Config_Simple($this->_all, false);
        $this->assertEquals('all', $config->hostname);
        $this->assertEquals('live', $config->db->name);
        $this->assertEquals('multi', $config->one->two->three);
        $this->assertFalse($config->nonexistent); // property doesn't exist
    }

    public function testIsset()
    {
        if (version_compare(PHP_VERSION, '5.1', '>=')) {
            $config = new Yaf_Config_Simple($this->_all, false);

            $this->assertFalse(isset($config->notarealkey));
            $this->assertTrue(isset($config->hostname)); // top level
            $this->assertTrue(isset($config->db->name)); // one level down
        }
    }

    /**
     * @group config
     * @group configSimple
     */
    public function testModification()
    {
        $config = new Yaf_Config_Simple($this->_all, false);

        // overwrite an existing key
        $this->assertEquals('thisname', $config->name);
        $config->name = 'anothername';
        $this->assertEquals('anothername', $config->name);
        // overwrite an existing multi-level key
        $this->assertEquals('multi', $config->one->two->three);
        $config->one->two->three = 'anothername';
        $this->assertEquals('anothername', $config->one->two->three);

        // create a new multi-level key
        $config->does = array('not'=> array('exist' => 'yet'));
        $this->assertEquals('yet', $config->does->not->exist);

    }

    /*
     Currently no exception is throwed when writing to readonly config
    public function testNoModifications()
    {
        $config = new Yaf_Config_Simple($this->_all);
        try {
            $config->hostname = 'test';
        } catch (Yaf_Config_Exception $expected) {
            $this->assertContains('is read only', $expected->getMessage());
            return;
        }
        if (!defined('YAF_MODE')) {
            $this->fail('An expected Yaf_Config_Exception has not been raised');
        }
    }

    public function testNoNestedModifications()
    {
        $config = new Yaf_Config_Simple($this->_all);
        try {
            $config->db->host = 'test';
        } catch (Yaf_Config_Exception $expected) {
            $this->assertContains('is read only', $expected->getMessage());
            return;
        }
        if (!defined('YAF_MODE')) {
            $this->fail('An expected Yaf_Config_Exception has not been raised');
        }
    }*/

    public function testNumericKeys()
    {
        if (defined('YAF_MODE')) {
            $this->markTestSkipped(
                'Yaf_Config_Simple does not allow number as key'
            );
            return;
        }
        $data = new Yaf_Config_Simple($this->_numericData);
        $this->assertEquals('test', $data->{1});
        $this->assertEquals(34, $data->{0});
    }

    public function testCount()
    {
        $data = new Yaf_Config_Simple($this->_menuData);
        $this->assertEquals(3, count($data->button));
    }

    public function testIterator()
    {
        // top level
        $config = new Yaf_Config_Simple($this->_all);
        $var = '';
        foreach ($config as $key=>$value) {
            if (is_string($value)) {
                $var .= "\nkey = $key, value = $value";
            }
        }
        $this->assertContains('key = name, value = thisname', $var);

        // 1 nest
        $var = '';
        foreach ($config->db as $key=>$value) {
            $var .= "\nkey = $key, value = $value";
        }
        $this->assertContains('key = host, value = 127.0.0.1', $var);

        // 2 nests
        $config = new Yaf_Config_Simple($this->_menuData);
        $var = '';
        foreach ($config->button->b1 as $key=>$value) {
            $var .= "\nkey = $key, value = $value";
        }
        $this->assertContains('key = L1, value = button1-1', $var);
    }

    public function testArray()
    {
        $config = new Yaf_Config_Simple($this->_all);

        ob_start();
        print_r($config->toArray());
        $contents = ob_get_clean();

        $this->assertContains('Array', $contents);
        $this->assertContains('[hostname] => all', $contents);
        $this->assertContains('[user] => username', $contents);
    }

    /*
    Currently no exception is throwed when writing to readonly config
    public function testErrorWriteToReadOnly()
    {
        $config = new Yaf_Config_Simple($this->_all);
        try {
            $config->test = '32';
        } catch (Yaf_Config_Exception $expected) {
            $this->assertContains('read only', $expected->getMessage());
            return;
        }
        if (!defined('YAF_MODE')) {
            $this->fail('An expected Yaf_Config_Exception has not been raised');
        }
    }*/

    public function testZF343()
    {
        $configArray = array(
            'controls' => array(
                'visible' => array(
                    'name' => 'visible',
                    'type' => 'checkbox',
                    'attribs' => array(), // empty array
                ),
            ),
        );
        $formConfig = new Yaf_Config_Simple($configArray, true);
        $this->assertSame(
            array(), $formConfig->controls->visible->attribs->toArray()
        );
    }

    public function testZF402()
    {
        $configArray = array(
            'data1'  => 'someValue',
            'data2'  => 'someValue',
            'false1' => false,
            'data3'  => 'someValue'
            );
        $config = new Yaf_Config_Simple($configArray);
        $this->assertTrue(count($config) === count($configArray));
        $count = 0;
        foreach ($config as $key => $value) {
            if ($key === 'false1') {
                $this->assertTrue($value === false);
            } else {
                $this->assertTrue($value === 'someValue');
            }
            $count++;
        }
        $this->assertTrue($count === 4);
    }

    public function testZf1019_HandlingInvalidKeyNames()
    {
        $config = new Yaf_Config_Simple($this->_leadingdot);
        $array = $config->toArray();
        $this->assertContains('dot-test', $array['.test']);
    }

    public function testZF1019_EmptyKeys()
    {
        $config = new Yaf_Config_Simple($this->_invalidkey);
        $array = $config->toArray();
        $this->assertContains('test', $array[' ']);
        $this->assertContains('test', $array['']);
    }

    public function testUnsetException()
    {
        // allow modifications is off - expect an exception
        $config = new Yaf_Config_Simple($this->_all, true);

        $this->assertTrue(isset($config->hostname)); // top level

        try {
            unset($config->hostname);
        } catch (Yaf_Config_Exception $expected) {
            $this->assertContains('is read only', $expected->getMessage());
            return;
        }
        if (!defined('YAF_MODE')) {
            $this->fail('Expected read only exception has not been raised.');
        }
    }

    public function testUnset()
    {
        if (defined('YAF_MODE')) {
            $this->markTestSkipped(
                'Yaf_Config_Simple unset does\'t do anything'
            );
            return;
        }
        // allow modifications is on
        $config = new Yaf_Config_Simple($this->_all, false);

        $this->assertTrue(isset($config->hostname));
        $this->assertTrue(isset($config->db->name));

        unset($config->hostname);
        unset($config->db->name);

        $this->assertFalse(isset($config->hostname));
        $this->assertFalse(isset($config->db->name));
    }

     // Ensures that toArray() supports objects of types other
     //than Yaf_Config_Simple
    public function testToArraySupportsObjects()
     {
        $configData = array(
            'a' => new stdClass(),
            'b' => array(
                'c' => new stdClass(),
                'd' => new stdClass()
                )
            );
        $config = new Yaf_Config_Simple($configData);
        $this->assertEquals($config->toArray(), $configData);
        $this->assertInstanceOf('stdClass', $config->a);
        $this->assertInstanceOf('stdClass', $config->b->c);
        $this->assertInstanceOf('stdClass', $config->b->d);
    }

    public function testUnsettingFirstElementDuringForeachDoesNotSkipAnElement()
    {
        $config = new Yaf_Config_Simple(
            array(
                'first'  => array(1),
                'second' => array(2),
                'third'  => array(3)
            ), false
        );

        $keyList = array();
        foreach ($config as $key => $value) {
            $keyList[] = $key;
            if ($key == 'first') {
                // uses magic Yaf_Config_Simple::__unset() method
                unset($config->$key);
            }
        }

        $this->assertEquals('first', $keyList[0]);
        $this->assertEquals('second', $keyList[1]);
        $this->assertEquals('third', $keyList[2]);
    }

    public function testUnsetAMiddleElementDuringForeachDoesNotSkipAnElement()
    {
        $config = new Yaf_Config_Simple(
            array(
                'first'  => array(1),
                'second' => array(2),
                'third'  => array(3)
            ), false
        );

        $keyList = array();
        foreach ($config as $key => $value) {
            $keyList[] = $key;
            if ($key == 'second') {
                // uses magic Yaf_Config_Simple::__unset() method
                unset($config->$key);
            }
        }

        $this->assertEquals('first', $keyList[0]);
        $this->assertEquals('second', $keyList[1]);
        $this->assertEquals('third', $keyList[2]);
    }

    public function testUnsettingLastElementDuringForeachDoesNotSkipAnElement()
    {
        $config = new Yaf_Config_Simple(
            array(
                'first'  => array(1),
                'second' => array(2),
                'third'  => array(3)
            ), false
        );

        $keyList = array();
        foreach ($config as $key => $value) {
            $keyList[] = $key;
            if ($key == 'third') {
                // uses magic Yaf_Config_Simple::__unset() method
                unset($config->$key);
            }
        }

        $this->assertEquals('first', $keyList[0]);
        $this->assertEquals('second', $keyList[1]);
        $this->assertEquals('third', $keyList[2]);
    }

    public function testZF6995_toArrayDoesNotDisturbInternalIterator()
    {
        $config = new Yaf_Config_Simple(range(1, 10));
        $config->rewind();
        $this->assertEquals(1, $config->current());

        $config->toArray();
        $this->assertEquals(1, $config->current());
    }

    public function testCase007()
    {
        $config = array(
            'section1' => array(
                'name' => 'value', 'dummy' => 'foo'
            ),
            'section2' => "laruence"
        );
        $configFirst = new Yaf_Config_Simple($config, 'section2');
        $this->assertEquals(
            $config['section1'],
            $configFirst->section1->toArray()
        );
        $this->assertEquals(
            $config['section2'],
            $configFirst->section2
        );
        $configSecond = new Yaf_Config_Simple($config, 'section2');
        $this->assertTrue($configSecond->readonly());
        $configSecond->new = "value";
        $this->assertFalse(isset($config->new));
        $configThird = new Yaf_Config_Simple($config);
        unset($config);
        $this->assertTrue(isset($configThird["section2"]));
        $configThird->new = "value";
        $this->assertFalse($configThird->readonly());
        $this->assertEquals(
            array(
                'section1' => array
                    (
                        'name' => 'value',
                        'dummy' => 'foo'
                    ),
                'section2' => 'laruence',
                'new' => 'value'
            ), $configThird->toArray()
        );
        $sick = new Yaf_Config_Simple(array());
        $this->assertFalse($sick->__isset(1));
        $this->assertFalse($sick->__get(2));
        $sick->total = 1;
        $this->assertEquals(1, count($sick));
        $this->assertEquals(1, $sick->total);
    }

    public function testBug61493()
    {
        $config = new Yaf_Config_Simple(
            array(
                'foo' => 'bar',
            ), false
        );
        $this->assertEquals('bar', $config->foo);
        unset($config['foo']);
        $this->assertFalse($config->foo);
    }
}