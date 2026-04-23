<?php

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $jawsdb_url = getenv('JAWSDB_URL');
        
        if ($jawsdb_url) {
            $db = parse_url($jawsdb_url);
            $host     = $db['host'];
            $user     = $db['user'];
            $password = $db['pass'];
            $dbname   = ltrim($db['path'], '/');
            $port     = $db['port'] ?? 3306;
        } else {
            // Docker
            $host     = getenv('MYSQL_HOST')     ?: 'localhost';
            $user     = getenv('MYSQL_USER')     ?: 'root';
            $password = getenv('MYSQL_PASSWORD') ?: '';
            $dbname   = getenv('MYSQL_DATABASE') ?: 'vite_et_gourmand';
            $port     = getenv('MYSQL_PORT')     ?: 3306;
        }

        $pdo = new PDO(
            "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4",
            $user,
            $password,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }
    return $pdo;
}