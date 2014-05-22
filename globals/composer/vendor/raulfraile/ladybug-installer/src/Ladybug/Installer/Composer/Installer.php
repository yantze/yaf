<?php

namespace Ladybug\Installer\Composer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;

class Installer extends LibraryInstaller
{

    const PACKAGE_TYPE_THEME = 'ladybug-theme';
    const PACKAGE_TYPE_PLUGIN = 'ladybug-plugin';

    /**
     * Determines the install path for ladybug themes and plugins
     *
     * @param PackageInterface $package
     *
     * @return string a path relative to the root of the composer.json that is being installed.
     */
    public function getInstallPath(PackageInterface $package)
    {
        return $this->getRootPath($package) . '/' . $this->extractName($package);
    }

    /**
     * Extract the theme/plugin name from the package extra info
     *
     * @param PackageInterface $package
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    protected function extractName(PackageInterface $package)
    {
        $extraData = $package->getExtra();

        if (!array_key_exists('ladybug_name', $extraData)) {
            throw new \InvalidArgumentException(
                'Unable to install theme/plugin, ladybug addons must '
                    .'include the name in the extra field of composer.json'
            );
        }

        return $extraData['ladybug_name'];
    }

    /**
     * Returns the root installation path for templates.
     *
     * @param PackageInterface $package
     *
     * @return string a path relative to the root of the composer.json
     */
    protected function getRootPath(PackageInterface $package)
    {
        $rootPath = $this->vendorDir . '/raulfraile/ladybug-themes/Ladybug/';

        if ($this->composer->getPackage()->getName() === 'raulfraile/ladybug') {
            $rootPath = 'data/' . ($package->getType() === self::PACKAGE_TYPE_THEME ? 'themes' : 'plugins') . '/Ladybug/';
        }

        $rootPath .= ($package->getType() === self::PACKAGE_TYPE_THEME) ? 'Theme' : 'Plugin';

        return $rootPath;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return in_array($packageType, array(
            self::PACKAGE_TYPE_THEME,
            self::PACKAGE_TYPE_PLUGIN
        ), true);
    }
}
