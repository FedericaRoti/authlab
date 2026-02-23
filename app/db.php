<?php

# For not include db.php multiple times
function db(): \PDO {
    #Create only one connection then reuse it
    static $pdo = null;
    if ($pdo) return $pdo;
    
    $config = require __DIR__ . '/config.php';

    # Database connection settings
    $host = $config['db']['host'];
    $db   = $config['db']['name'];
    $user = $config['db']['user'];
    $pass = $config['db']['pass'];
    $charset = $config['db']['charset'];
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    
    # Create a PDO instance
    try{
        $pdo = new \PDO($dsn, $user, $pass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    } catch (\PDOException $e) {
        die("Database connection failed: " . $e->getMessage());     
    }
}

