<?php

namespace UbUnibeCh\PKPInstaller;


use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class AppInstaller extends LibraryInstaller
{

    const APP_DIRECTORY = 'app/';

    /**
     * @inheritDoc
     */
    public function getInstallPath(PackageInterface $package)
    {
        
        return realpath($this->vendorDir . '/..') . '/' . self::APP_DIRECTORY;
    }

    /**
     * @inheritDoc
     */
    public function supports($packageType)
    {
        return 'pkp-app' === $packageType;
    }
}