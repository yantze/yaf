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

class SilexMetadata extends AbstractMetadata
{

    const ICON = 'silex';
    const URL = 'http://silex.sensiolabs.org/api/index.html?q=%class%';

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->version = null;
    }

    /**
     * @inheritdoc
     */
    public function supports(VariableWrapper $data)
    {
        return VariableWrapper::TYPE_CLASS === $data->getType() && $this->isNamespace($data->getId(), 'Silex');
    }

    /**
     * @inheritdoc
     */
    public function get(VariableWrapper $data)
    {
        if ($this->supports($data)) {
            return array(
                'help_link' => $this->generateHelpLinkUrl(self::URL, array(
                    '%class%' => urlencode($data->getId())
                )),
                'icon' => self::ICON
            );
        }

        return array();
    }

}
