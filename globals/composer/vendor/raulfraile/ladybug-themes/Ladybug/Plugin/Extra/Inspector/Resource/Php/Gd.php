<?php
/*
 * Ladybug: Simple and Extensible PHP Dumper
 *
 * GD dumper
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

class Gd extends AbstractInspector
{

    /**
     * @inheritdoc
     */
    public function supports(VariableWrapper $data)
    {
        return VariableWrapper::TYPE_RESOURCE == $data->getType() &&
            'gd' === $data->getId();
    }

    /**
     * @inheritdoc
     */
    public function get(VariableWrapper $data)
    {
        if (!$this->supports($data)) {
            throw new \Ladybug\Exception\InvalidInspectorClassException();
        }

        $var = $data->getData();

        $width = imagesx($var);
        $height = imagesy($var);

        // get image content
        ob_start();
        imagepng($var);
        $imageContent = ob_get_clean();

        $image = $this->createImageType($imageContent, 'Image');
        $image->setLevel($this->level);
        $image->setWidth($width);
        $image->setHeight($height);
        $image->setTitle('Image');

        return $image;
    }
}
