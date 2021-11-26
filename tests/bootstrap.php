<?php

if (!defined('MODULE_ROOT_DIR')) {
    define('MODULE_ROOT_DIR', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR);
}
if (!defined('APPLICATION_ROOT_DIR')) {
    define('APPLICATION_ROOT_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR);
}

if (!defined('APPLICATION_VENDOR_DIR')) {
    define('APPLICATION_VENDOR_DIR', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR);
}
if (!defined('APPLICATION_STORE')) {
    define('APPLICATION_STORE', 'DE');
}
if (!defined('APPLICATION_ENV')) {
    define('APPLICATION_ENV', 'dev');
}
