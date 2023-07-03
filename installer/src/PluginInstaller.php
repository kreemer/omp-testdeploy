<?php

namespace UbUnibeCh\PKPInstaller;


use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class PluginInstaller extends LibraryInstaller
{
    /**
     * @inheritDoc
     */
    public function getInstallPath(PackageInterface $package)
    {
        $packageName = $package->getExtra()['pkp-plugin-name'] ?? null;
        if ($packageName === null) {       
            $packageParts = explode('/', $package->getPrettyName());
            if ($packageParts === false) {
                $packageName = $package->getPrettyName();
            } else {
                $packageName = end($packageParts);
            }
        }
        
        $packageType = $package->getType();
        if ($packageType === 'pkp-theme') {
            $pluginType = 'themes';
        } else {
            $pluginType = $package->getExtra()['pkp-plugin-type'] ?? 'generic';
        }



        return 'app/plugins/' . $pluginType . '/' . $packageName;
    }

    /**
     * @inheritDoc
     */
    public function supports($packageType)
    {
        return 'pkp-plugin' === $packageType || 'pkp-theme' === $packageType;
    }
}