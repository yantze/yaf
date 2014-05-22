<?php

namespace Ladybug\Tests\Plugin\Extra\Metadata;

use Ladybug\Plugin\Extra\ExtraPlugin;

class ExtraPluginTest extends \PHPUnit_Framework_TestCase
{

    public function testConfigFileExists()
    {
        $this->assertFileExists(ExtraPlugin::getConfigFile());
    }

}