<?php

namespace Ladybug\Tests\Plugin\Extra\Metadata;

use Ladybug\Plugin\Extra\Metadata\TwigMetadata;
use Ladybug\Model\VariableWrapper;
use \Mockery as m;

class TwigMetadataTest extends \PHPUnit_Framework_TestCase
{

    /** @var TwigMetadata */
    protected $metadata;

    public function setUp()
    {
        $this->metadata = new TwigMetadata();
    }

    public function testMetadataForValidValues()
    {
        $className = 'Twig_Environment';
        $data = new VariableWrapper($className, m::mock($className));

        $this->assertTrue($this->metadata->supports($data));

        $metadata = $this->metadata->get($data);
        $this->assertArrayHasKey('help_link', $metadata);
        $this->assertArrayHasKey('icon', $metadata);
        $this->assertEquals('twig', $metadata['icon']);
    }

    public function testMetadataForInvalidValues()
    {
        $data = new VariableWrapper('\stdClass', new \stdClass());

        $this->assertFalse($this->metadata->supports($data));

        $metadata = $this->metadata->get($data);
        $this->assertEmpty($metadata);
    }

}
