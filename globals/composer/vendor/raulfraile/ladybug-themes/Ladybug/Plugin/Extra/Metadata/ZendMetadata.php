<?php
/*
 * Ladybug: Simple and Extensible PHP Dumper
 *
 * Processor / Standard Object
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

class ZendMetadata extends AbstractMetadata
{

    const ICON = 'zend';
    const URL = 'http://framework.zend.com/apidoc/%version%/namespaces/%class%.html';

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->version = '2.2';
    }

    /**
     * @inheritdoc
     */
    public function supports(VariableWrapper $data)
    {
        return VariableWrapper::TYPE_CLASS === $data->getType() && $this->isNamespace($data->getId(), 'Zend');
    }

    /**
     * @inheritdoc
     */
    public function get(VariableWrapper $data)
    {
        if ($this->supports($data)) {
            return array(
                'help_link' => $this->generateHelpLinkUrl(self::URL, array(
                    '%version%' => $this->version,
                    '%class%' => urlencode($data->getId())
                )),
                'icon' => self::ICON
            );
        }

        return array();
    }

}
