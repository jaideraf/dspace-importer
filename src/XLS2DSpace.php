<?php

require_once realpath('C:\Users\04574440961\vendor/autoload.php');
require 'XLS2DSpaceThesisSynchronizer.php';

function printUsage()
{
    echo "Usage: XLS2DSpace.php -c <config file>" . PHP_EOL;
}

$configFile = "";
$opts = getopt("c:");
if (array_key_exists("c", $opts)) {
    $configFile = $opts["c"];
} else {
    printUsage();
    die;
}

$sync = new XLS2DSpaceThesisSynchronizer($configFile);
$sync->syncRepos();
