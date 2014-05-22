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

class AuraMetadata extends AbstractMetadata
{

    const ICON = 'aura';
    const URL = 'http://auraphp.github.io/Aura.%component%/version/%version%/api/classes/%class%.html';

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->version = '1.1.0';
    }

    /**
     * @inheritdoc
     */
    public function supports(VariableWrapper $data)
    {
        return VariableWrapper::TYPE_CLASS === $data->getType() && $this->isNamespace($data->getId(), 'Aura');
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
                    '%component%' => $this->getComponent($data->getId()),
                    '%class%' => str_replace('\\', '.', $data->getId())
                )),
                'icon' => self::ICON,
                'version' => $this->version
            );
        }

        return array();
    }

    /**
     * Gets the Aura component from a class name
     * @param string $className Class name
     *
     * @return string
     */
    protected function getComponent($className)
    {
        $namespace = explode('\\', $className);

        return $namespace[1];
    }
}
