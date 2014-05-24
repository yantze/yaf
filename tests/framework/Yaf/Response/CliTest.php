<?php
/**
 * @group Yaf
 * @group YafResponse
 * @group YafResponseCli
 */
class YafResponseCliTest extends PHPUnit_Framework_TestCase
{
    public function testCase05()
    {
        $response = new Yaf_Response_Cli();
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
        ob_start();
        $response->response();
        $content = ob_get_clean();
        $this->assertEquals(
            'laruenceifjakdsljfklasdjfkljasdkljfkljadsf'.
            'kfjdaksljfklajdsfkljasdkljfkjasdf',
            $content
        );
    }
}