<?php

namespace Ladybug\Tests\Plugin\Extra\Inspector\Resource\Php;

use Ladybug\Inspector;
use Ladybug\Type;
use Ladybug\Model\VariableWrapper;
use Ladybug\Inspector\InspectorInterface;
use Ladybug\Plugin\Extra\Inspector\Resource\Php\File as FileInspector;
use Ladybug\Plugin\Extra\Type as TypeExtra;
use \Mockery as m;

class FileTest extends \PHPUnit_Framework_TestCase
{

    /** @var FileInspector */
    protected $inspector;

    public function setUp()
    {
        $factoryTypeMock = m::mock('Ladybug\Type\FactoryType');
        $factoryTypeMock->shouldReceive('factory')->with(m::any(), m::any())->andReturn(new Type\IntType());

        $extendedTypeFactoryMock = m::mock('Ladybug\Type\ExtendedTypeFactory');
        $extendedTypeFactoryMock->shouldReceive('factory')->with('collection', m::any())->andReturn(new TypeExtra\CollectionType());
        $extendedTypeFactoryMock->shouldReceive('factory')->with('unixpermissions', m::any())->andReturn(new TypeExtra\UnixPermissionsType());
        $extendedTypeFactoryMock->shouldReceive('factory')->with('size', m::any())->andReturn(new TypeExtra\SizeType());
        $extendedTypeFactoryMock->shouldReceive('factory')->with('text', m::any())->andReturn(new TypeExtra\TextType());
        $extendedTypeFactoryMock->shouldReceive('factory')->with('code', m::any())->andReturn(new TypeExtra\CodeType());

        $this->inspector = new FileInspector($factoryTypeMock, $extendedTypeFactoryMock);
    }

    public function testForValidValues()
    {
        $var = fopen(__DIR__ . '/../../../files/test.txt', 'rb');

        $data = new VariableWrapper('file', $var, VariableWrapper::TYPE_RESOURCE);

        $result = $this->inspector->get($data);

        $this->assertInstanceOf('Ladybug\Plugin\Extra\Type\CollectionType', $result);
    }

    public function testForInvalidValues()
    {
        $this->setExpectedException('Ladybug\Exception\InvalidInspectorClassException');

        $data = new VariableWrapper('\stdClass', new \stdClass());

        $this->inspector->get($data);
    }

}
