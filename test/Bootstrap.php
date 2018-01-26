<?php
declare(strict_types=1);

mb_internal_encoding('UTF-8');
chdir(dirname(__DIR__));

$vendorPath = findParentPath('vendor');
if (is_readable($vendorPath . '/autoload.php')) {
    $loader = include($vendorPath . '/autoload.php');
} else {
    throw new RuntimeException('vendor/autoload.php could not be found. Did you run `php composer.phar install`?');
}

$configFile =  __DIR__ . '/config.php';
if (getenv('integration_test_file')) {
    $configFile =  __DIR__ . '/config_scrutinizer.php';
}

require_once $configFile;


/**
 * Search upwards for a certain directory
 *
 * @param string $path
 * @return string
 */
function findParentPath($path)
{
    $dir = __DIR__;
    $previousDir = '.';
    while (!is_dir($dir . '/' . $path)) {
        $dir = dirname($dir);
        if ($previousDir === $dir) {
            return false;
        }
        $previousDir = $dir;
    }
    return $dir . '/' . $path;
}
