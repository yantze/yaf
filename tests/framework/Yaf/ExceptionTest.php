<?php
/**
 * @group Yaf
 * @group YafException
 */
class YafExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testCase015()
    {
        $previous = new Yaf_Exception("Previous", 100);
        $exception = new Yaf_Exception(
            "Exception", 200, $previous
        );
        $this->assertSame($previous, $exception->getPrevious());
        $this->assertEquals('Exception', $exception->getMessage());
        $this->assertEquals(
            100, $exception->getPrevious()->getCode()
        );
    }
}