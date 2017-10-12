<?php

if (count($argv)<2) die('Not enough arguments. Path to config file is required.');

$filename = $argv[1];

$configFile = file_get_contents($filename);
$lines = explode(PHP_EOL, $configFile);

$uniqueLines = [];
$newLines = [];

foreach ($lines as $line) {
    if (substr($line, 0, 4) !== 'use ' || (substr($line, 0, 4) === 'use ' && !isset($uniqueLines[$line]))) {
        $uniqueLines[$line] = 1;
        $newLines[] = $line;
    }
}

file_put_contents($filename, implode(PHP_EOL, $newLines));
