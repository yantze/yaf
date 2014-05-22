<?php

namespace Ladybug\Tests\Plugin\Extra\Inspector\Object\Php;

use Ladybug\Inspector;
use Ladybug\Type;
use Ladybug\Model\VariableWrapper;
use Ladybug\Inspector\InspectorInterface;
use Ladybug\Plugin\Extra\Inspector\Object\Php\SplQueue as SplQueueInspector;
use Ladybug\Plugin\Extra\Type\CollectionType;
use \Mockery as m;

class SplQueueTest extends \PHPUnit_Framework_TestCase
{

    /** @var SplQueueInspector $inspector */
    protected $inspector;

    public function setUp()
    {
        $factoryTypeMock = m::mock('Ladybug\Type\FactoryType');
        $factoryTypeMock->shouldReceive('factory')->with(m::anyOf(1, 2, 3), m::any())->andReturn(new Type\IntType());

        $extendedTypeFactoryMock = m::mock('Ladybug\Type\ExtendedTypeFactory');
        $extendedTypeFactoryMock->shouldReceive('factory')->with('collection', m::any())->andReturn(new CollectionType());

        $this->inspector = new SplQueueInspector($factoryTypeMock, $extendedTypeFactoryMock);
    }

    public function testForValidValues()
    {
        $var = new \SplQueue();
        $var->push(1);
        $var->push(2);
        $var->push(3);

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
