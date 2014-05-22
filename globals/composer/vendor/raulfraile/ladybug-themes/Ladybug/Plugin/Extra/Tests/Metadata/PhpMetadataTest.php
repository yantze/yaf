<?php

namespace Ladybug\Tests\Plugin\Extra\Metadata;

use Ladybug\Plugin\Extra\Metadata\PhpMetadata;
use Ladybug\Model\VariableWrapper;
use \Mockery as m;

class PhpMetadataTest extends \PHPUnit_Framework_TestCase
{

    /** @var PhpMetadata */
    protected $metadata;

    public function setUp()
    {
        $this->metadata = new PhpMetadata();
    }

    public function testMetadataForValidValues()
    {
        $data = new VariableWrapper('DateTime', new \DateTime());

        $this->assertTrue($this->metadata->supports($data));

        $metadata = $this->metadata->get($data);
        $this->assertArrayHasKey('help_link', $metadata);
        $this->assertArrayHasKey('icon', $metadata);
        $this->assertArrayHasKey('version', $metadata);
        $this->assertEquals(phpversion(), $this->metadata->getVersion());
        $this->assertEquals('php', $metadata['icon']);
    }

    public function testMetadataForInvalidValues()
    {
        $data = new VariableWrapper('\stdClass', new \stdClass());

        $this->assertFalse($this->metadata->supports($data));

        $metadata = $this->metadata->get($data);
        $this->assertEmpty($metadata);
    }

}
