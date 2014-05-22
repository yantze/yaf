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

class DoctrineMetadata extends AbstractMetadata
{

    const ICON = 'doctrine';

    const URL_ORM = 'http://www.doctrine-project.org/api/orm/%version%/class-%class%.html';
    const URL_DBAL = 'http://www.doctrine-project.org/api/dbal/%version%/class-%class%.html';
    const URL_ODM = 'http://www.doctrine-project.org/api/mongodb_odm/%version%/class-%class%.html';
    const URL_COMMON = 'http://www.doctrine-project.org/api/common/%version%/class-%class%.html';

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->version = '2.3';
    }

    /**
     * @inheritdoc
     */
    public function supports(VariableWrapper $data)
    {
        return VariableWrapper::TYPE_CLASS === $data->getType() && $this->isNamespace($data->getId(), 'Doctrine');
    }

    /**
     * @inheritdoc
     */
    public function get(VariableWrapper $data)
    {
        if ($this->supports($data)) {

            $helpLink = '#';
            $id = $data->getId();

            if ($this->isNamespace($id, 'Doctrine\\ORM')) {
                $helpLink = $this->generateHelpLinkUrl(self::URL_ORM, array(
                    '%version%' => $this->version,
                    '%class%' => str_replace('\\', '.', $id)
                ));
            } elseif ($this->isNamespace($id, 'Doctrine\\DBAL')) {
                $helpLink = $this->generateHelpLinkUrl(self::URL_DBAL, array(
                    '%version%' => $this->version,
                    '%class%' => str_replace('\\', '.', $id)
                ));
            } elseif ($this->isNamespace($id, 'Doctrine\\ODM')) {
                $helpLink = $this->generateHelpLinkUrl(self::URL_ODM, array(
                    '%version%' => $this->version,
                    '%class%' => str_replace('\\', '.', $id)
                ));
            } elseif ($this->isNamespace($id, 'Doctrine\\Common')) {
                $helpLink = $this->generateHelpLinkUrl(self::URL_ORM, array(
                    '%version%' => $this->version,
                    '%class%' => str_replace('\\', '.', $id)
                ));
            }

            return array(
                'help_link' => $helpLink,
                'icon' => self::ICON,
                'version' => $this->version
            );
        }

        return array();
    }

}
