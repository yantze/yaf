<?php

/*
 * This file is part of the Ladybug package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ladybug\Theme\Modern;

use Ladybug\Theme\AbstractTheme;
use Ladybug\Theme\HtmlThemeInterface;
use Ladybug\Format;

/**
 * Modern theme class
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class ModernTheme extends AbstractTheme implements HtmlThemeInterface
{
    /**
     * Gets the theme name
     *
     * @return string
     */
    public function getName()
    {
        return 'Modern';
    }

    /**
     * Gets the theme parent
     *
     * @return string
     */
    public function getParent()
    {
        return 'Base';
    }

    /**
     * Gets CSS dependencies
     *
     * @return array
     */
    public function getHtmlCssDependencies()
    {
        return array(
            'lib/bootstrap/css/bootstrap.min.css',
            'lib/codemirror/lib/codemirror.css',
            'css/styles.css'
        );
    }

    /**
     * Gets JS dependencies
     *
     * @return array
     */
    public function getHtmlJsDependencies()
    {
        return array(
            'lib/jquery/jquery.min.js',
            'lib/bootstrap/js/bootstrap.min.js',
            'lib/codemirror/lib/codemirror.js',
            'lib/codemirror/mode/clike/clike.js',
            'lib/codemirror/mode/css/css.js',
            'lib/codemirror/mode/htmlmixed/htmlmixed.js',
            'lib/codemirror/mode/htmlembedded/htmlembedded.js',
            'lib/codemirror/mode/javascript/javascript.js',
            'lib/codemirror/mode/http/http.js',
            'lib/codemirror/mode/php/php.js',
            'lib/codemirror/mode/xml/xml.js'
        );
    }

    /**
     * Gets supported formats
     *
     * @return array
     */
    public function getFormats()
    {
        return array(
            Format\HtmlFormat::FORMAT_NAME
        );
    }
}
