<?php

namespace Ladybug\Installer\Composer;

require __DIR__ . '/../../../../vendor/composer/composer/tests/Composer/Test/TestCase.php';

use Composer\Util\Filesystem;
use Composer\Test\TestCase;
use Composer\Composer;
use Composer\Config;
use Composer\Package\RootPackage;
use Composer\Downloader\DownloadManager;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\IO\IOInterface;
use \Mockery as m;

class InstallerTest extends TestCase
{

    /** @var Composer $composer */
    protected $composer;

    /** @var Config $config */
    protected $config;

    /** @var string $vendorDir */
    protected $vendorDir;

    /** @var string $binDir */
    protected $binDir;

    /** @var DownloadManager $downloadManager */
    protected $downloadManager;

    /** @var InstalledRepositoryInterface $repository */
    protected $repository;

    /** @var IOInterface $io */
    protected $io;

    /** @var Filesystem $filesystem */
    protected $filesystem;

    /** @var RootPackage $package */
    protected $package;

    protected function setUp()
    {
        $this->filesystem = new Filesystem;

        $this->composer = new Composer();
        $this->config = new Config();
        $this->composer->setConfig($this->config);

        $this->package = new RootPackage('raulfraile/ladybug', '1.0.0', '1.0.0');
        $this->composer->setPackage($this->package);

        $this->vendorDir = realpath(sys_get_temp_dir()).DIRECTORY_SEPARATOR
            .'composer-test-vendor';
        $this->ensureDirectoryExistsAndClear($this->vendorDir);

        $this->binDir = realpath(sys_get_temp_dir()).DIRECTORY_SEPARATOR
            .'composer-test-bin';
        $this->ensureDirectoryExistsAndClear($this->binDir);

        $this->config->merge(
            array(
                'config' => array(
                    'vendor-dir' => $this->vendorDir,
                    'bin-dir'    => $this->binDir,
                ),
            )
        );

        $this->downloadManager = m::mock('Composer\Downloader\DownloadManager');
        $this->composer->setDownloadManager($this->downloadManager);

        $this->repository = m::mock('Composer\Repository\InstalledRepositoryInterface');
        $this->io = m::mock('Composer\IO\IOInterface');
    }

    protected function tearDown()
    {
        $this->filesystem->removeDirectory($this->vendorDir);
        $this->filesystem->removeDirectory($this->binDir);
    }

    public function testGetInstallPathForThemes()
    {
        $library = new Installer($this->io, $this->composer);
        $package = $this->createThemePackageMock('Test');

        $this->assertEquals(
            'data/themes/Ladybug/Theme/Test',
            $library->getInstallPath($package)
        );
    }

    public function testGetInstallPathForPlugins()
    {
        $library = new Installer($this->io, $this->composer);
        $package = $this->createPluginPackageMock('Test');

        $this->assertEquals(
            'data/plugins/Ladybug/Plugin/Test',
            $library->getInstallPath($package)
        );
    }

    public function testGetInstallPathWhenVendored()
    {
        $composer = $this->createComposerMock();
        $rootPackage = new RootPackage('test/theme_test', '1.0.0.0', '1.0.0');
        $composer->setPackage($rootPackage);

        $library = new Installer($this->io, $composer);

        $package = $this->createThemePackageMock('Test');

        $this->assertEquals(
            $this->vendorDir.'/raulfraile/ladybug-themes/Ladybug/Theme/Test',
            $library->getInstallPath($package)
        );
    }

    public function testGetInstallPathWithoutName()
    {
        $this->setExpectedException('InvalidArgumentException');
        $library = new Installer($this->io, $this->composer);
        $package = $this->createPluginPackageMock();

        $library->getInstallPath($package);
    }

    public function testSupports()
    {
        $library = new Installer($this->io, $this->composer);

        $this->assertTrue($library->supports('ladybug-theme'));
        $this->assertTrue($library->supports('ladybug-plugin'));
        $this->assertFalse($library->supports('library'));
    }

    protected function createPackageMock($ladybugName = null)
    {
        $package = m::mock('Composer\Package\Package', array(md5(rand()), '1.0.0.0', '1.0.0'));

        $extra = array();
        if (!is_null($ladybugName)) {
            $extra = array(
                'ladybug_name' => $ladybugName
            );
        }

        $package->shouldReceive('getExtra')->andReturn($extra);

        return $package;
    }

    protected function createThemePackageMock($ladybugName = null)
    {
        $package = $this->createPackageMock($ladybugName);
        $package->shouldReceive('getType')->andReturn('ladybug-theme');

        return $package;
    }

    protected function createPluginPackageMock($ladybugName = null)
    {
        $package = $this->createPackageMock($ladybugName);
        $package->shouldReceive('getType')->andReturn('ladybug-plugin');

        return $package;
    }

    protected function createComposerMock()
    {
        $composer = new Composer();
        $composer->setConfig($this->config);
        $composer->setDownloadManager($this->downloadManager);

        return $composer;
    }
}
