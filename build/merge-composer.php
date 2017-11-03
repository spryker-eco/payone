<?php

const PATH_REQUIRE = 'require';

if (count($argv) < 4) {
    echo 'Not enough arguments!' . PHP_EOL;
    die(1);
}

$fromComposer = json_decode(file_get_contents($argv[1]), true);
$demoShopComposer = json_decode(file_get_contents($argv[2]), true);
foreach ($fromComposer[PATH_REQUIRE] as $module => &$version) {
    if (isset($demoShopComposer[PATH_REQUIRE][$module])
        && $demoShopComposer[PATH_REQUIRE][$module] > $version
        && strpos($version, $demoShopComposer[PATH_REQUIRE][$module]) === false)
    {
        print sprintf("Changing %s '%s' to '%s'", $module, $version, $demoShopComposer[PATH_REQUIRE][$module]) . PHP_EOL;
        $version = $demoShopComposer[PATH_REQUIRE][$module];
    }
}

file_put_contents($argv[3], json_encode($fromComposer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL);

