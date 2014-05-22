<?php

/*
 * This file is part of the Ladybug package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ladybug\Theme\Base;

use Ladybug\Theme\AbstractTheme;
use Ladybug\Theme\HtmlThemeInterface;
use Ladybug\Theme\CliThemeInterface;
use Ladybug\Format;

/**
 * Base theme class
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class BaseTheme extends AbstractTheme implements HtmlThemeInterface, CliThemeInterface
{

    /**
     * Gets the theme name
     *
     * @return string
     */
    public function getName()
    {
        return 'Base';
    }

    /**
     * Gets the theme parent
     *
     * @return string
     */
    public function getParent()
    {
        return null;
    }

    /**
     * Gets CLI styles
     *
     * @return array
     */
    public function getCliStyles()
    {
        return array();
    }

    /**
     * Gets CLI dependencies
     *
     * @return array
     */
    public function getCliTags()
    {
        return array();
    }

    /**
     * Gets CSS dependencies
     *
     * @return array
     */
    public function getHtmlCssDependencies()
    {
        return array();
    }

    /**
     * Gets JS dependencies
     *
     * @return array
     */
    public function getHtmlJsDependencies()
    {
        return array();
    }

    /**
     * Gets supported formats
     *
     * @return array
     */
    public function getFormats()
    {
        return array(
            Format\HtmlFormat::FORMAT_NAME,
            Format\ConsoleFormat::FORMAT_NAME,
            Format\TextFormat::FORMAT_NAME,
            Format\JsonFormat::FORMAT_NAME,
            Format\XmlFormat::FORMAT_NAME,
            Format\YamlFormat::FORMAT_NAME,
            Format\PhpFormat::FORMAT_NAME
        );
    }

}
