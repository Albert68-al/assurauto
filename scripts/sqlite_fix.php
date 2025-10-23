<?php
// Simple script to clear sqlite journal by switching journal mode and vacuuming
$dbPath = __DIR__ . '/../database/database.sqlite';
try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('PRAGMA journal_mode = DELETE');
    $pdo->exec('VACUUM');
    echo "sqlite fix ok\n";
} catch (Exception $e) {
    echo "sqlite fix failed: " . $e->getMessage() . "\n";
}
