<?php
/*
 * Ladybug: Simple and Extensible PHP Dumper
 *
 * Object/SplMaxHeap dumper
 *
 * (c) Raúl Fraile Beneyto <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ladybug\Plugin\Extra\Inspector\Object\Php;

use Ladybug\Inspector\InspectorInterface;
use Ladybug\Model\VariableWrapper;

class SplMaxHeap extends SplHeap
{

    /**
     * @inheritdoc
     */
    public function supports(VariableWrapper $data)
    {
        return VariableWrapper::TYPE_CLASS == $data->getType() && 'SplMaxHeap' === $data->getId();
    }

}
