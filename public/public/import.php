<?php
$jawsdb_url = getenv('JAWSDB_URL');
$db = parse_url($jawsdb_url);
$host     = $db['host'];
$user     = $db['user'];
$password = $db['pass'];
$dbname   = ltrim($db['path'], '/');

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $user,
        $password
    );
    
    $sql = file_get_contents(__DIR__ . '/../database/create.sql');
    $pdo->exec($sql);
    echo "create.sql importé !<br>";
    
    $sql2 = file_get_contents(__DIR__ . '/../database/insert.sql');
    $pdo->exec($sql2);
    echo "insert.sql importé !<br>";
    
    echo "Base de données créée avec succès !";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}