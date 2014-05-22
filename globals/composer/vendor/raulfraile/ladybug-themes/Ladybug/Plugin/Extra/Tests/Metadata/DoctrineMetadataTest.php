<?php

namespace Ladybug\Tests\Plugin\Extra\Metadata;

use Ladybug\Plugin\Extra\Metadata\DoctrineMetadata;
use Ladybug\Model\VariableWrapper;
use \Mockery as m;

class DoctrineMetadataTest extends \PHPUnit_Framework_TestCase
{

    /** @var DoctrineMetadata */
    protected $metadata;

    public function setUp()
    {
        $this->metadata = new DoctrineMetadata();
    }

    public function testMetadataForDoctrineOrm()
    {
        $className = 'Doctrine\ORM\EntityManager';
        $data = new VariableWrapper($className, m::mock($className));

        $this->assertTrue($this->metadata->supports($data));

        $metadata = $this->metadata->get($data);
        $this->assertArrayHasKey('help_link', $metadata);
        $this->assertArrayHasKey('icon', $metadata);
        $this->assertArrayHasKey('version', $metadata);
        $this->assertEquals('doctrine', $metadata['icon']);
    }

    public function testMetadataForDoctrineDbal()
    {
        $className = 'Doctrine\DBAL\Driver\Connection';
        $data = new VariableWrapper($className, m::mock($className));

        $this->assertTrue($this->metadata->supports($data));

        $metadata = $this->metadata->get($data);
        $this->assertArrayHasKey('help_link', $metadata);
        $this->assertArrayHasKey('icon', $metadata);
        $this->assertArrayHasKey('version', $metadata);
        $this->assertEquals('doctrine', $metadata['icon']);
    }

    public function testMetadataForDoctrineOdm()
    {
        $className = 'Doctrine\ODM\MongoDB';
        $data = new VariableWrapper($className, m::mock($className));

        $this->assertTrue($this->metadata->supports($data));

        $metadata = $this->metadata->get($data);
        $this->assertArrayHasKey('help_link', $metadata);
        $this->assertArrayHasKey('icon', $metadata);
        $this->assertArrayHasKey('version', $metadata);
        $this->assertEquals('doctrine', $metadata['icon']);
    }

    public function testMetadataForDoctrineCommon()
    {
        $className = 'Doctrine\Common\Collections\ArrayCollection';
        $data = new VariableWrapper($className, m::mock($className));

        $this->assertTrue($this->metadata->supports($data));

        $metadata = $this->metadata->get($data);
        $this->assertArrayHasKey('help_link', $metadata);
        $this->assertArrayHasKey('icon', $metadata);
        $this->assertArrayHasKey('version', $metadata);
        $this->assertEquals('doctrine', $metadata['icon']);
    }

    public function testMetadataForInvalidValues()
    {
        $data = new VariableWrapper('\stdClass', new \stdClass());

        $this->assertFalse($this->metadata->supports($data));

        $metadata = $this->metadata->get($data);
        $this->assertEmpty($metadata);
    }

}
