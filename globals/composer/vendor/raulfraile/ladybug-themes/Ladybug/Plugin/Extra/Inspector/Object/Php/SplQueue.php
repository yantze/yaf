<?php
/*
 * Ladybug: Simple and Extensible PHP Dumper
 *
 * Object/SplQueue dumper
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
use Ladybug\Plugin\Extra\Type\CollectionType;

class SplQueue extends AbstractInspector
{

    /**
     * @inheritdoc
     */
    public function supports(VariableWrapper $data)
    {
        return VariableWrapper::TYPE_CLASS == $data->getType() && 'SplQueue' === $data->getId();
    }

    /**
     * @inheritdoc
     */
    public function get(VariableWrapper $data)
    {
        if (!$this->supports($data)) {
            throw new \Ladybug\Exception\InvalidInspectorClassException();
        }

        /** @var $var \SplQueue */

        $arrayData = iterator_to_array($data->getData());

        /** @var $collection CollectionType */
        $collection = $this->extendedTypeFactory->factory('collection', $this->level);

        $collection->setTitle('Queue');

        foreach ($arrayData as $item) {
            $collection->add($this->typeFactory->factory($item, $this->level + 1));
        }

        return $collection;
    }

}
