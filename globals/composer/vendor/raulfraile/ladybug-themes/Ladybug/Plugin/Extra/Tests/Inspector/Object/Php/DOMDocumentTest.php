<?php

namespace Ladybug\Tests\Plugin\Extra\Inspector\Object\Php;

use Ladybug\Inspector;
use Ladybug\Type;
use Ladybug\Model\VariableWrapper;
use Ladybug\Inspector\InspectorInterface;
use Ladybug\Plugin\Extra\Inspector\Object\Php\DOMDocument as InspectorDOMDocument;
use Ladybug\Plugin\Extra\Type\CodeType;
use \Mockery as m;

class DOMDocumentTest extends \PHPUnit_Framework_TestCase
{

    /** @var InspectorDOMDocument */
    protected $inspector;

    public function setUp()
    {
        $factoryTypeMock = m::mock('Ladybug\Type\FactoryType');
        $factoryTypeMock->shouldReceive('factory')->with(m::anyOf(1, 2, 3), m::any())->andReturn(new Type\IntType());

        $extendedTypeFactoryMock = m::mock('Ladybug\Type\ExtendedTypeFactory');
        $extendedTypeFactoryMock->shouldReceive('factory')->with('code', m::any())->andReturn(new CodeType());

        $this->inspector = new InspectorDOMDocument($factoryTypeMock, $extendedTypeFactoryMock);
    }

    public function testForValidValues()
    {
        $var = new \DOMDocument();

        $data = new VariableWrapper(get_class($var), $var);

        $result = $this->inspector->get($data);

        $this->assertInstanceOf('Ladybug\Plugin\Extra\Type\CodeType', $result);
        $this->assertEquals('xml', $result->getLanguage());
    }

    public function testForInvalidValues()
    {
        $this->setExpectedException('Ladybug\Exception\InvalidInspectorClassException');

        $data = new VariableWrapper('\stdClass', new \stdClass());

        $this->inspector->get($data);
    }

}
