<?php
/*
 * Ladybug: Simple and Extensible PHP Dumper
 *
 * Mysql/Link dumper
 *
 * (c) Raúl Fraile Beneyto <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ladybug\Plugin\Extra\Inspector\Resource\Php;

use Ladybug\Inspector\AbstractInspector;
use Ladybug\Inspector\InspectorInterface;
use Ladybug\Model\VariableWrapper;
use Ladybug\Plugin\Extra\Type\CollectionType;

class MysqlLink extends AbstractInspector
{

    /**
     * @inheritdoc
     */
    public function supports(VariableWrapper $data)
    {
        return VariableWrapper::TYPE_RESOURCE == $data->getType() &&
            'mysql link' === $data->getId();
    }

    /**
     * @inheritdoc
     */
    public function get(VariableWrapper $data)
    {
        if (!$this->supports($data)) {
            throw new \Ladybug\Exception\InvalidInspectorClassException();
        }

        /** @var $collection CollectionType */
        $collection = $this->extendedTypeFactory->factory('collection', $this->level);

        $var = $data->getData();
        $collection->add($this->createTextType(mysql_get_host_info($var), 'Host info'));
        $collection->add($this->createTextType(mysql_get_proto_info($var), 'Protocol version'));
        $collection->add($this->createTextType(mysql_get_server_info($var), 'Server version'));

        $collection->setTitle('MySQL connection');
        $collection->setLevel($this->level);

        return $collection;
    }
}
