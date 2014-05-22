<?php
/*
 * Ladybug: Simple and Extensible PHP Dumper
 *
 * Processor / AuraMetadata
 *
 * (c) Raúl Fraile Beneyto <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ladybug\Plugin\Extra\Metadata;

use Ladybug\Metadata\MetadataInterface;
use Ladybug\Metadata\AbstractMetadata;
use Ladybug\Model\VariableWrapper;

class MysqlMetadata extends AbstractMetadata
{

    const ICON = 'mysql';

    /**
     * @inheritdoc
     */
    public function supports(VariableWrapper $data)
    {
        return 'mysql' === substr($data->getId(), 0, 5) && VariableWrapper::TYPE_RESOURCE === $data->getType();
    }

    /**
     * @inheritdoc
     */
    public function get(VariableWrapper $data)
    {
        if ($this->supports($data)) {
            return array(
                'icon' => self::ICON,
                'version' => $this->version
            );
        }

        return array();
    }

}
