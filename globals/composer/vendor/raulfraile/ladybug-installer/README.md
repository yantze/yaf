Ladybug addon installer
=================

[![Build Status](https://secure.travis-ci.org/raulfraile/ladybug-installer.png)](http://travis-ci.org/raulfraile/ladybug-installer)
[![Latest Stable Version](https://poser.pugx.org/raulfraile/ladybug-installer/v/stable.png)](https://packagist.org/packages/raulfraile/ladybug-installer)
[![Total Downloads](https://poser.pugx.org/raulfraile/ladybug-installer/downloads.png)](https://packagist.org/packages/raulfraile/ladybug-installer)
[![Latest Unstable Version](https://poser.pugx.org/raulfraile/ladybug-installer/v/unstable.png)](https://packagist.org/packages/raulfraile/ladybug-installer)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5e05254a-e34a-45ae-8eeb-305a29be802b/mini.png)](https://insight.sensiolabs.com/projects/5e05254a-e34a-45ae-8eeb-305a29be802b)

Ladybug relies on specific directory locations to find themes and plugins.
By default [Composer](http://getcomposer.org) is unable to install in an other
directory than /vendor except when using a
[Custom Installer](http://getcomposer.org/doc/articles/custom-installers.md).

This Custom Installer for Composer will trigger on the following library types
and provide custom behaviour for these kind of packages:

* ladybug-theme: install files into /vendor/raulfraile/ladybug-themes
* ladybug-plugin: install files into /vendor/raulfraile/ladybug-plugins

## Credits

* Raul Fraile ([@raulfraile](https://twitter.com/raulfraile))
* [All contributors](https://github.com/raulfraile/ladybug-installer/contributors)

## License

Ladybug is released under the MIT License. See the bundled LICENSE file for details.