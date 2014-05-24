<?php
/**
 * @group Yaf
 * @group YafSession
 * @runaTestsInSeparateProcesses
 */
class YafSessionTest extends PHPUnit_Framework_TestCase
{
    public $session=null;

    public function setUp()
    {
        // session
        $this->session = Yaf_Session::getInstance();
    }

    /**
     * @runInSeparateProcess
     */
    public function testCase016()
    {
        $_SESSION["name"] = "Laruence";
        $age = 28;
        $this->session->age = $age;
        $this->assertEquals($age, $this->session->age);
        $this->assertEquals($_SESSION['age'], $this->session->age);
        $session2 = Yaf_Session::getInstance();
        $session2['company'] = 'Baidu';
        $this->assertTrue(isset($session2->age));
        $this->assertTrue($session2->has('age'));
        $this->assertTrue($session2->has('name'));
        $this->assertEquals(2, count($session2));
        $currentInSession = $_SESSION;
        foreach ($session2 as $key => $value) {
            $this->assertEquals($currentInSession[$key], $value);
        }
        unset($session2);
        $session3 = Yaf_Session::getInstance();
        $session3->del("name");
        $this->assertFalse($session3->has('name'));
        $this->assertFalse(isset($session3->name));
        unset($session3["company"]);
        unset($session3->age);
        $this->assertEquals(0, count($session3));
    }
}
