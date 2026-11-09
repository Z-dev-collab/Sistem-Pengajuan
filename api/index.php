<?php

// Setup storage in /tmp for serverless environment
$storage = '/tmp/storage';
putenv("APP_STORAGE={$storage}");
$_ENV['APP_STORAGE'] = $storage;

$dirs = [
    $storage . '/framework/views',
    $storage . '/framework/cache',
    $storage . '/framework/cache/data',
    $storage . '/framework/sessions',
    $storage . '/logs',
    $storage . '/app/public',
];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Prepare SQLite database in /tmp
$dbDir = '/tmp/database';
$dbFile = '/tmp/database/database.sqlite';
if (!file_exists($dbFile)) {
    if (!is_dir($dbDir)) {
        @mkdir($dbDir, 0777, true);
    }
    if (file_exists(__DIR__ . '/../database/database.sqlite')) {
        @copy(__DIR__ . '/../database/database.sqlite', $dbFile);
    } else {
        @touch($dbFile);
    }
}
putenv("DB_DATABASE={$dbFile}");
$_ENV['DB_DATABASE'] = $dbFile;

require __DIR__ . '/../public/index.php';
