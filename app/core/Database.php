<?php
declare(strict_types=1);
final class Database
{

    private static ?PDO $connection = null;

    private function __construct() 
    {
    }

    public static function getInstance(): PDO
    {
        if (self::$connection === null) {
            $config = require_once __DIR__ . '/config.php';
            $dsn = "mysql:host=" . $config['host'] . ";dbname=" . $config['dbname'] . ";charset=utf8mb4";
            
            try {
                self::$connection = new PDO(
                    $dsn,
                    $config['username'],
                    $config['password'],
                    $config['options']
                );
            } catch (PDOException $e) {
                throw new PDOException("Erreur de connexion : " . $e->getMessage());
            }
        }
        
        return self::$connection;
    }

    public function __clone()
    {
    }

    public function __wakeup()
    {
    }
}