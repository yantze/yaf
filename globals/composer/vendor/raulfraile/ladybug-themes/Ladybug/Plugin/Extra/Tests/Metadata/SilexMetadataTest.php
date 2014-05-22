<?php

namespace Ladybug\Tests\Plugin\Extra\Metadata;

use Ladybug\Plugin\Extra\Metadata\SilexMetadata;
use Ladybug\Model\VariableWrapper;
use \Mockery as m;

class SilexMetadataTest extends \PHPUnit_Framework_TestCase
{

    /** @var SilexMetadata */
    protected $metadata;

    public function setUp()
    {
        $this->metadata = new SilexMetadata();
    }

    public function testMetadataForValidValues()
    {
        $className = 'Silex\Application';
        $data = new VariableWrapper($className, m::mock($className));

        $this->assertTrue($this->metadata->supports($data));

        $metadata = $this->metadata->get($data);
        $this->assertArrayHasKey('help_link', $metadata);
        $this->assertArrayHasKey('icon', $metadata);
        $this->assertEquals('silex', $metadata['icon']);
    }

    public function testMetadataForInvalidValues()
    {
        $data = new VariableWrapper('\stdClass', new \stdClass());

        $this->assertFalse($this->metadata->supports($data));

        $metadata = $this->metadata->get($data);
        $this->assertEmpty($metadata);
    }

}
