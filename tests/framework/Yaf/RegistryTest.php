<?php
/**
 * @group Yaf
 * @group YafRegistry
 */
class YafRegistryTest extends PHPUnit_Framework_TestCase
{
    public function testCase004()
    {
        $str = "Ageli Platform";
        Yaf_Registry::set("name", $str);
        unset($str);
        $this->assertEquals(
            'Ageli Platform', Yaf_Registry::get("name")
        );
        $this->assertTrue(Yaf_Registry::has("name"));
        $name = "name";
        Yaf_Registry::del($name);
        $this->assertNull(Yaf_Registry::get("name"));
        $this->assertFalse(Yaf_Registry::has("name"));
    }
}