<?php

namespace silverorange\DevTest;

require __DIR__ . '/../vendor/autoload.php'; 

use silverorange\DevTest\Console\PostImporterConsole;

$config = new Config();
$db = (new Database($config->dsn))->getConnection();


$availableCommands = [
    'import:posts' => new PostImporterConsole($db),
];

$command = $argv[1] ?? null;

if (!$command || !isset($availableCommands[$command])) {
    echo "Invalid or missing command.\n";
    echo "Available commands:\n";
    foreach (array_keys($availableCommands) as $name) {
        echo "  - $name\n";
    }
    exit;
}

$availableCommands[$command]->execute();
