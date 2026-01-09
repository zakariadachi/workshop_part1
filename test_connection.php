<?php
declare(strict_types=1);

require_once __DIR__ . '/app/core/Database.php';

try {
    $db = Database::getInstance();
    echo "Connexion réussie !\n";
} catch (PDOException $e) {
    echo "ERREUR : " . $e->getMessage() . "\n";
}