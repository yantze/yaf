<?php

namespace Ladybug\Tests\Plugin\Extra\Inspector\Resource\Php;

use Ladybug\Inspector;
use Ladybug\Type;
use Ladybug\Model\VariableWrapper;
use Ladybug\Inspector\InspectorInterface;
use Ladybug\Plugin\Extra\Inspector\Resource\Php\Gd as GdInspector;
use Ladybug\Plugin\Extra\Type as TypeExtra;
use \Mockery as m;

class GdTest extends \PHPUnit_Framework_TestCase
{

    /** @var GdInspector */
    protected $inspector;

    public function setUp()
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped(
                'The GD extension is not available.'
            );
        }

        $factoryTypeMock = m::mock('Ladybug\Type\FactoryType');
        $factoryTypeMock->shouldReceive('factory')->with(m::any(), m::any())->andReturn(new Type\IntType());

        $extendedTypeFactoryMock = m::mock('Ladybug\Type\ExtendedTypeFactory');
        $extendedTypeFactoryMock->shouldReceive('factory')->with('collection', m::any())->andReturnUsing(function() {return new TypeExtra\CollectionType();});
        $extendedTypeFactoryMock->shouldReceive('factory')->with('text', m::any())->andReturn(new TypeExtra\TextType());
        $extendedTypeFactoryMock->shouldReceive('factory')->with('image', m::any())->andReturn(new TypeExtra\ImageType());

        $this->inspector = new GdInspector($factoryTypeMock, $extendedTypeFactoryMock);
    }

    public function testForValidValues()
    {
        $var = imagecreatefrompng(__DIR__ . '/../../../files/ladybug.png');

        $data = new VariableWrapper('gd', $var, VariableWrapper::TYPE_RESOURCE);

        $result = $this->inspector->get($data);

        $this->assertInstanceOf('Ladybug\Type\ExtendedTypeInterface', $result);
    }

    public function testForInvalidValues()
    {
        $this->setExpectedException('Ladybug\Exception\InvalidInspectorClassException');

        $data = new VariableWrapper('\stdClass', new \stdClass());

        $this->inspector->get($data);
    }

}
