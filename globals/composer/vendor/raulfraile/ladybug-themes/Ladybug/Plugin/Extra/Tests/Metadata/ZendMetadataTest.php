<?php

namespace Ladybug\Tests\Plugin\Extra\Metadata;

use Ladybug\Plugin\Extra\Metadata\ZendMetadata;
use Ladybug\Model\VariableWrapper;
use \Mockery as m;

class ZendMetadataTest extends \PHPUnit_Framework_TestCase
{

    /** @var ZendMetadata */
    protected $metadata;

    public function setUp()
    {
        $this->metadata = new ZendMetadata();
    }

    public function testMetadataForValidValues()
    {
        $className = 'Zend\Authentication\Adapter\Http\Exception';
        $data = new VariableWrapper($className, m::mock($className));

        $this->assertTrue($this->metadata->supports($data));

        $metadata = $this->metadata->get($data);
        $this->assertArrayHasKey('help_link', $metadata);
        $this->assertArrayHasKey('icon', $metadata);
        $this->assertEquals('zend', $metadata['icon']);
    }

    public function testMetadataForInvalidValues()
    {
        $data = new VariableWrapper('\stdClass', new \stdClass());

        $this->assertFalse($this->metadata->supports($data));

        $metadata = $this->metadata->get($data);
        $this->assertEmpty($metadata);
    }

}
