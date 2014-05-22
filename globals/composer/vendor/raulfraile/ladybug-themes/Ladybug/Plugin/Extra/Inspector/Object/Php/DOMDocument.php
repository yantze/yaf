<?php
/*
 * Ladybug: Simple and Extensible PHP Dumper
 *
 * Object/DomDocument dumper
 *
 * (c) Raúl Fraile Beneyto <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ladybug\Plugin\Extra\Inspector\Object\Php;

use Ladybug\Inspector\AbstractInspector;
use Ladybug\Inspector\InspectorInterface;
use Ladybug\Model\VariableWrapper;
use Ladybug\Plugin\Extra\Type\CodeType;

class DOMDocument extends AbstractInspector
{

    public function supports(VariableWrapper $data)
    {
        return VariableWrapper::TYPE_CLASS == $data->getType() && 'DOMDocument' === $data->getId();
    }

    public function get(VariableWrapper $data)
    {
        if (!$this->supports($data)) {
            throw new \Ladybug\Exception\InvalidInspectorClassException();
        }

        /** @var \DOMDocument $var */
        $var = $data->getData();

        $var->formatOutput = true;
        $xml = $var->saveXML();

        /** @var $code CodeType */
        $code = $this->extendedTypeFactory->factory('code', $this->level);

        $code->setLanguage('xml');
        $code->setContent($xml);
        $code->setKey('Code');
        $code->setTitle('XML');

        return $code;
    }

}
