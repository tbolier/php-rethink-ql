<?php
declare(strict_types=1);

mb_internal_encoding('UTF-8');

require_once __DIR__ . '/../vendor/autoload.php';

$configFile =  __DIR__ . '/config.php';
if (getenv('integration_test_file')) {
    $configFile =  __DIR__ . '/config_scrutinizer.php';
}

require_once $configFile;
