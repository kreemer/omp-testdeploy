<?php

namespace UbUnibeCh\PKPInstaller;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PluginInterface;

class AppInstallerPlugin implements PluginInterface
{

    private function getInstallers(Composer $composer, IOInterface $io)
    {
        yield new AppInstaller($io, $composer);
        yield new PluginInstaller($io, $composer);
    }

    public function activate(Composer $composer, IOInterface $io)
    {
        foreach ($this->getInstallers($composer, $io) as $installer) {
            $composer->getInstallationManager()->addInstaller($installer);
        }
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {

    }

    public function uninstall(Composer $composer, IOInterface $io) 
    {

    }

}