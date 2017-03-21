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

        $currentDirectory = getcwd();
        $bundleDirectory = realpath(__DIR__.'/../');
        /*
        $status = null;
        $output = array();

        chdir($bundleDirectory);
        */
        $command = 'bower install --allow-root -F';
        $process = new Process($command, $bundleDirectory);
        $process->run();
        //exec('bower install --allow-root -F', $output, $status);
        /*
        chdir($currentDirectory);

        foreach($output as $line) {
            echo $line."\n";
        }

        if ($status) {
            throw new \RuntimeException("Running 'bower install' failed with $status\n");
        }
*/
        if (!$process->isSuccessful()) {
            throw new \RuntimeException("Running 'bower install' failed from directory ".$bundleDirectory.". Descritption error: ".$process->getErrorOutput());
        }
    }
}