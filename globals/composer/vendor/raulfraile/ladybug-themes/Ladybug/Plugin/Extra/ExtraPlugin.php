<?php
/*
 * Ladybug: Simple and Extensible PHP Dumper
 *
 * AbstractPlugin class
 *
 * @author Raúl Fraile Beneyto <raulfraile@gmail.com> || @raulfraile
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ladybug\Plugin\Extra;

use Ladybug\Plugin\PluginInterface;

class ExtraPlugin implements PluginInterface
{

    /**
     * @inheritdoc
     */
    public static function getConfigFile()
    {
        return __DIR__ . '/Resources/services.xml';
    }

    /**
     * @inheritdoc
     */
    public static function registerHelpers()
    {
        return array(
            'ladybug_set_theme',
            'ladybug_set_format',
            'ladybug_set_option',
            'ladybug_set_options',
            'ladybug_dump',
            'ladybug_dump_die',
            'ladybug_dump_class',
            'ladybug_dump_class_die',
            'ld',
            'ldd',
            'ldc',
            'ldcd'
        );
    }

}
