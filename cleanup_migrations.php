<?php

// Supprimer la migration en double
$migrationFile = __DIR__ . '/database/migrations/2025_10_24_055028_create_activity_logs_table.php';

if (file_exists($migrationFile)) {
    unlink($migrationFile);
    echo "Migration en double supprimée avec succès!\n";
} else {
    echo "La migration n'existe pas ou a déjà été supprimée.\n";
}

// Supprimer les fichiers temporaires
$tempFiles = [
    __DIR__ . '/fix_migration.php',
    __DIR__ . '/fix_migration.sql'
];

foreach ($tempFiles as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "Fichier temporaire supprimé: " . basename($file) . "\n";
    }
}

echo "\nNettoyage terminé!\n";
