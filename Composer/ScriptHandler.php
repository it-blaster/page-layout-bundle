<?php

namespace Etfostra\PageLayoutBundle\Composer;


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
        $status = null;
        $output = array();

        chdir($bundleDirectory);
        exec('bower install', $output, $status);
        chdir($currentDirectory);

        foreach($output as $line) {
            echo $line."\n";
        }

        if ($status) {
            throw new \RuntimeException("Running 'bower install' failed with $status\n");
        }
    }
}