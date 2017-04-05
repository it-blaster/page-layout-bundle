<?php

namespace Etfostra\PageLayoutBundle\Composer;
use Symfony\Component\Process\Process;


/**
 * Class ScriptHandler
 * @package Etfostra\PageLayoutBundle\Composer
 */
class ScriptHandler
{
    public static function installGridStackJs()
    {
        $bundleDirectory = realpath(__DIR__.'/..');

        $command = 'bower install --allow-root -F';
        $process = new Process($command, $bundleDirectory);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException("Running 'bower install' failed from directory ".$bundleDirectory.". Descritption error: ".$process->getErrorOutput());
        }
    }
}