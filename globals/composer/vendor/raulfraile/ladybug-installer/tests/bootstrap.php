<?php

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('Composer\Test', __DIR__.'/../vendor/composer/composer/tests');
$loader->add('Ladybug', __DIR__);

if (!class_exists('\\Mockery')) {
    echo "You must install the dev dependencies using:\n";
    echo "    composer install --dev\n";
    exit(1);
}
