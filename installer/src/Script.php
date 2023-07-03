<?php

namespace UbUnibeCh\PKPInstaller;


use Composer\Composer;
use Composer\Installer\InstallerEvent;
use Composer\Package\Link;
use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;
use Composer\Script\Event;

class Script
{

    public static function updateConfig(Event $event)
    {
        $configList = [];
        $keys = array_keys($_SERVER);
        foreach ($keys as $key) {
            if (!str_starts_with($key, 'PKP_')) {
                continue;
            }
            $configList[] = $key;
        }

        if ($event->getIO()->isInteractive()) {
            $event->getIO()->write('Found ' . count($configList) . ' keys to substitute');
            if ($event->getIO()->isVerbose()) {
                foreach ($configList as $config) {
                    $event->getIO()->write('- ' . $config);
                }
            }
        }


        $pkpPackage = self::findPkpApp($event->getComposer());

        if ($pkpPackage === null) {
            $event->getIO()->writeError('Could not find pkp package link!');
        }

        $installer = $event->getComposer()->getInstallationManager()->getInstaller('pkp-app');
        
        $installPath = $installer->getInstallPath($pkpPackage);
        if (!file_exists($installPath) || !file_exists(($installPath . '/config.inc.php'))) {
            $event->getIO()->error('Can not update the config when not installed');
        }



        $actualConfig = parse_ini_file($installPath . '/config.inc.php', true);
        $structuredConfig = [];
        foreach ($configList as $configKey) {
            $value = $_SERVER[$configKey];
            $configKeyParts = explode('_', strtolower($configKey));

            array_shift($configKeyParts);
            $section = array_shift($configKeyParts);
            $structuredConfig[$section][implode('_', $configKeyParts)] = $value;
        }

        var_dump($structuredConfig);
        


        /*
        $composer = $event->getComposer();



        $packages = $composer->getPackage()->getRequires();
        $link = null;
        foreach ($packages as $package) {
            if (str_starts_with($package->getTarget(), 'pkp/')) {
                $link  = $package;
                break;
            }
        }
        
        if ($link === null) {
            $event->getIO()->writeError('Could not find pkp package link!');
            return;
        }

        assert($link instanceof Link);


        $package = $composer->getRepositoryManager()->findPackage($link->getTarget(), $link->getConstraint());
        var_dump($package->getType());
        
        var_dump($package->getName());
        var_dump($installer->supports($package));
        var_dump($installer->getInstallPath($package));
        

        /*

        var_dump(get_class($event));

        $installer = $composer->getInstallationManager()->getInstaller(AppInstaller::class);
        var_dump($installer->getInstallPath($composer->getPackage()));
        
        $basePath = __DIR__ . '/../../';

        var_dump($basePath);
        
        return;/*



        var_dump(->getInstallPath($pkpPackage));
        //$iniFile = parse_ini_file(__DIR__)*/


    }

    private static function findPkpApp(Composer $composer)
    {
        $packages = $composer->getPackage()->getRequires();
        $link = null;
        foreach ($packages as $package) {
            if (str_starts_with($package->getTarget(), 'pkp/')) {
                $link  = $package;
                break;
            }
        }
        
        if ($link === null) {
            return null;
        }


        return $composer->getRepositoryManager()->findPackage($link->getTarget(), $link->getConstraint());
    }

}