# \Suin\Yaf\Twig

Twig extension for Yet Another Framework.


## Installation

You can install via Composer.

At first create `composer.json` file:

```json
{
	"require": {
		"suin/php-yaf-twig": ">=1.0"
	}
}
```

Run composer to install.

```
$ composer install
```

Finally, include `vendor/autoload.php` at `index.php`

```
require_once 'vendor/autoload.php';
```

Add to `Bootstrap.php`:

```php
<?php

use \Suin\Yaf\Twig\Twig;

class Bootstrap extends Yaf_Bootstrap_Abstract
{

	/**
	 * @param Yaf_Dispatcher $dispatcher
	 */
	protected function _initTwig(Yaf_Dispatcher $dispatcher)
	{
		$config = Yaf_Application::app()->getConfig()
		$dispatcher->setView(new Twig(APP_PATH.'views', $config->twig->toArray()));
	}
}
```

Add to `application.ini`:

```ini
[product]

;app
application.view.ext = twig

;twig
twig.cache = APP_PATH "../cache"

[devel : product]

;twig
twig.debug = true
```

## License

MIT license