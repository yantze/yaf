<?php
/**
 * @group Yaf
 * @group YafResponse
 * @group YafResponseHttp
 */
class YafResponseHttpTest extends PHPUnit_Framework_TestCase
{
    public function testCase05()
    {
        $response = new Yaf_Response_Http();
        $body  = 'ifjakdsljfklasdjfkljasdkljfkljadsf';
        $string = "laruence";

        $response->appendBody($body);
        $response->prependBody($string);
        $response->appendBody("kfjdaksljfklajdsfkljasdkljfkjasdf");

        $this->assertEquals(
            'laruenceifjakdsljfklasdjfkljasdkljfkljadsf'.
            'kfjdaksljfklajdsfkljasdkljfkjasdf',
            $response->getBody()
        );
        unset($string);
        $this->assertEquals(
            'laruenceifjakdsljfklasdjfkljasdkljfkljadsf'.
            'kfjdaksljfklajdsfkljasdkljfkjasdf',
            $response->getBody()
        );
        unset($body);
        $this->assertEquals(
            'laruenceifjakdsljfklasdjfkljasdkljfkljadsf'.
            'kfjdaksljfklajdsfkljasdkljfkjasdf',
            $response->getBody()
        );
    }

    public function testCase026()
    {
        $response = new Yaf_Response_Http();
        $response->setBody("ell")->appendBody("o")->prependBody("H");
        $this->assertEquals(
            'Hello',
            $response->getBody()
        );
    }
}