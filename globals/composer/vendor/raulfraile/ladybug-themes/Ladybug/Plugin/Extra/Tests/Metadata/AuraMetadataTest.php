<?php

namespace Ladybug\Tests\Plugin\Extra\Metadata;

use Ladybug\Plugin\Extra\Metadata\AuraMetadata;
use Ladybug\Model\VariableWrapper;
use \Mockery as m;

class AuraMetadataTest extends \PHPUnit_Framework_TestCase
{

    /** @var AuraMetadata */
    protected $metadata;

    public function setUp()
    {
        $this->metadata = new AuraMetadata();
    }

    public function testMetadataForValidValues()
    {
        $className = 'Aura\DI';

        $data = new VariableWrapper($className, m::mock($className));

        $this->assertTrue($this->metadata->supports($data));

        $metadata = $this->metadata->get($data);
        $this->assertArrayHasKey('help_link', $metadata);
        $this->assertArrayHasKey('icon', $metadata);
        $this->assertArrayHasKey('version', $metadata);
        $this->assertEquals('aura', $metadata['icon']);
    }

    public function testMetadataForInvalidValues()
    {
        $data = new VariableWrapper('\stdClass', new \stdClass());

        $this->assertFalse($this->metadata->supports($data));

        $metadata = $this->metadata->get($data);
        $this->assertEmpty($metadata);
    }

}
