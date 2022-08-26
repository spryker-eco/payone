<?php

$stores = [];

$stores['DE'] = [
    'queuePools' => [
        'synchronizationPool' => [
            'DE-connection',
        ],
    ],
    'locales' => [
        'de' => 'de_DE',
    ],
    'countries' => [
        'DE',
        'GB',
    ],
];
$stores['AT']['queuePools']['synchronizationPool'] = ['DE-connection'];
$stores['US']['queuePools']['synchronizationPool'] = ['DE-connection'];

return $stores;
