<?php

namespace Ladybug\Tests\Plugin\Extra\Inspector\Object\Php;

use Ladybug\Inspector;
use Ladybug\Type;
use Ladybug\Model\VariableWrapper;
use Ladybug\Inspector\InspectorInterface;
use Ladybug\Plugin\Extra\Inspector\Object\Php\SplMinHeap as SplMinHeapInspector;
use Ladybug\Plugin\Extra\Type\CollectionType;
use \Mockery as m;

class SplMinHeapTest extends \PHPUnit_Framework_TestCase
{

    /** @var SplMinHeapInspector */
    protected $inspector;

    public function setUp()
    {
        $factoryTypeMock = m::mock('Ladybug\Type\FactoryType');
        $factoryTypeMock->shouldReceive('factory')->with(m::anyOf(1, 2, 3), m::any())->andReturn(new Type\IntType());

        $extendedTypeFactoryMock = m::mock('Ladybug\Type\ExtendedTypeFactory');
        $extendedTypeFactoryMock->shouldReceive('factory')->with('collection', m::any())->andReturn(new CollectionType());

        $this->inspector = new SplMinHeapInspector($factoryTypeMock, $extendedTypeFactoryMock);
    }

    public function testForValidValues()
    {
        $var = new \SplMinHeap();
        $var->insert(1);
        $var->insert(2);
        $var->insert(3);

        $data = new VariableWrapper(get_class($var), $var);

        $result = $this->inspector->get($data);

        $this->assertInstanceOf('Ladybug\Plugin\Extra\Type\CollectionType', $result);
        $this->assertCount(3, $result);
    }

    public function testForInvalidValues()
    {
        $this->setExpectedException('Ladybug\Exception\InvalidInspectorClassException');

        $data = new VariableWrapper('\stdClass', new \stdClass());

        $this->inspector->get($data);
    }

}
