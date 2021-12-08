<?php
/**
 * Copyright © 2021-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\PropelConfig;
use Spryker\Zed\PropelOrm\Business\Builder\ExtensionObjectBuilder;
use Spryker\Zed\PropelOrm\Business\Builder\ExtensionQueryBuilder;
use Spryker\Zed\PropelOrm\Business\Builder\ObjectBuilder;
use Spryker\Zed\PropelOrm\Business\Builder\QueryBuilder;

$config[ErrorHandlerConstants::ERROR_LEVEL] = E_ALL;
$config[KernelConstants::ENABLE_CONTAINER_OVERRIDING] = true;
$config[KernelConstants::PROJECT_NAMESPACE] = 'Pyz';
$config[KernelConstants::PROJECT_NAMESPACES] = [
    'Pyz',
];
$config[KernelConstants::CORE_NAMESPACES] = [
    'SprykerShop',
    'SprykerEco',
    'Spryker',
    'SprykerSdk',
];

$config[PropelConstants::ZED_DB_ENGINE]
    = strtolower(getenv('SPRYKER_DB_ENGINE') ?: '') ?: PropelConfig::DB_ENGINE_MYSQL;
$config[PropelConstants::ZED_DB_HOST] = getenv('DATABASE_HOST') ?: 'mysql';
$config[PropelConstants::ZED_DB_PORT] = getenv('DATABASE_PORT') ?: '3306';
$config[PropelConstants::ZED_DB_USERNAME] = getenv('DATABASE_USERNAME') ?: 'root';
$config[PropelConstants::ZED_DB_PASSWORD] = getenv('DATABASE_PASSWORD') ?: 'secret';
$config[PropelConstants::ZED_DB_DATABASE] = getenv('DATABASE_NAME') ?: 'eu-docker';
$config[PropelConstants::USE_SUDO_TO_MANAGE_DATABASE] = false;

$placeholder = '%s:host=%s;port=%d;dbname=%s';

$dsn = sprintf(
    $placeholder,
    $config[PropelConstants::ZED_DB_ENGINE],
    $config[PropelConstants::ZED_DB_HOST],
    $config[PropelConstants::ZED_DB_PORT],
    $config[PropelConstants::ZED_DB_DATABASE]
);

$slaves = [];
foreach ($config[PropelConstants::ZED_DB_REPLICAS] ?? [] as $slaveData) {
    $slaves[] = [
        'dsn' => sprintf(
            $placeholder,
            $config[PropelConstants::ZED_DB_ENGINE],
            $slaveData[PropelConstants::ZED_DB_HOST],
            $slaveData[PropelConstants::ZED_DB_PORT],
            $config[PropelConstants::ZED_DB_DATABASE]
        ),
        'user' => $config[PropelConstants::ZED_DB_USERNAME],
        'password' => $config[PropelConstants::ZED_DB_PASSWORD],
    ];
}

$connections = [
    'pgsql' => [
        'adapter' => PropelConfig::DB_ENGINE_PGSQL,
        'dsn' => $dsn,
        'user' => $config[PropelConstants::ZED_DB_USERNAME],
        'password' => $config[PropelConstants::ZED_DB_PASSWORD],
        'slaves' => $slaves,
        'settings' => [],
    ],
    'mysql' => [
        'adapter' => PropelConfig::DB_ENGINE_MYSQL,
        'dsn' => $dsn,
        'user' => $config[PropelConstants::ZED_DB_USERNAME],
        'password' => $config[PropelConstants::ZED_DB_PASSWORD],
        'slaves' => $slaves,
        'settings' => [
            'charset' => 'utf8',
            'queries' => [
                'utf8' => 'SET NAMES utf8 COLLATE utf8_unicode_ci, COLLATION_CONNECTION = utf8_unicode_ci, COLLATION_DATABASE = utf8_unicode_ci, COLLATION_SERVER = utf8_unicode_ci',
            ],
        ],
    ],
];

$config[PropelConstants::PROPEL] = [
    'database' => [
        'connections' => [],
    ],
    'runtime' => [
        'defaultConnection' => 'default',
        'connections' => ['default', 'zed'],
    ],
    'generator' => [
        'defaultConnection' => 'default',
        'connections' => ['default', 'zed'],
        'objectModel' => [
            'defaultKeyType' => 'fieldName',
            'builders' => [
                // If you need full entity logging on Create/Update/Delete, then switch to
                // Spryker\Zed\PropelOrm\Business\Builder\ObjectBuilderWithLogger instead.
                'object' => ObjectBuilder::class,
                'objectstub' => ExtensionObjectBuilder::class,
                'query' => QueryBuilder::class,
                'querystub' => ExtensionQueryBuilder::class,
            ],
        ],
    ],
    'paths' => [
        'phpDir' => APPLICATION_ROOT_DIR,
        'sqlDir' => APPLICATION_ROOT_DIR . 'src/Orm/Propel/Sql',
        'migrationDir' => APPLICATION_ROOT_DIR . 'src/Orm/Propel/Migration_' . $config[PropelConstants::ZED_DB_ENGINE],
        'schemaDir' => APPLICATION_ROOT_DIR . 'src/Orm/Propel/Schema',
    ],
];

$ENGINE = $config[PropelConstants::ZED_DB_ENGINE];
$config[PropelConstants::PROPEL]['database']['connections']['default'] = $connections[$ENGINE];
$config[PropelConstants::PROPEL]['database']['connections']['zed'] = $connections[$ENGINE];
