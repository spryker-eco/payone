<?php

use Spryker\Shared\Config\Config;

if (!defined('APPLICATION')) {
    define('APPLICATION', 'Payone');
}

if (!defined('APPLICATION_ROOT_DIR')) {
    define('APPLICATION_ROOT_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR);
}

if (!defined('APPLICATION_VENDOR_DIR')) {
    define('APPLICATION_VENDOR_DIR', APPLICATION_ROOT_DIR . 'vendor');
}

if (!defined('APPLICATION_STORE')) {
    define('APPLICATION_STORE', 'DE');
}

if (!defined('APPLICATION_ENV')) {
    define('APPLICATION_ENV', 'development');
}

if (!defined('APPLICATION_SOURCE_DIR')) {
    define('APPLICATION_SOURCE_DIR', APPLICATION_ROOT_DIR . 'src' . DIRECTORY_SEPARATOR);
}

if (!defined('MODULE_UNDER_TEST_ROOT_DIR')) {
    define('MODULE_UNDER_TEST_ROOT_DIR', '');
}

// Copy config files
$configSourceDirectory = APPLICATION_ROOT_DIR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'Shared' . DIRECTORY_SEPARATOR;
$configTargetDirectory = APPLICATION_ROOT_DIR . 'config' . DIRECTORY_SEPARATOR . 'Shared' . DIRECTORY_SEPARATOR;

if (!is_dir($configTargetDirectory)) {
    mkdir($configTargetDirectory, 0777, true);
}

spl_autoload_register(function ($className) {
    if (
        strrpos($className, 'Transfer') === false
        && strrpos($className, 'Builder') === false
        && strrpos($className, 'Spy') === false
    ) {
        return false;
    }

    $classNameParts = explode('\\', $className);

    $fileName = implode(DIRECTORY_SEPARATOR, $classNameParts) . '.php';
    $filePath = APPLICATION_ROOT_DIR . 'src' . DIRECTORY_SEPARATOR . $fileName;

    if (!file_exists($filePath)) {
        return false;
    }

    require_once $filePath;

    return true;
});

copy($configSourceDirectory . 'config_local.php', $configTargetDirectory . 'config_local.php');
copy($configSourceDirectory . 'config_propel.php', $configTargetDirectory . 'config_propel.php');
copy($configSourceDirectory . 'default_store.php', $configTargetDirectory . 'default_store.php');
copy($configSourceDirectory . 'stores.php', $configTargetDirectory . 'stores.php');

$config = Config::getInstance();

$config->init();
