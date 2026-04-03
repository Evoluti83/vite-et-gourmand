<?php
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/config/db.php';

try {
    $pdo = getDB();
    
    $sql = file_get_contents(__DIR__ . '/../database/create.sql');
    $pdo->exec($sql);
    echo "create.sql importé !<br>";
    
    $sql2 = file_get_contents(__DIR__ . '/../database/insert.sql');
    $pdo->exec($sql2);
    echo "insert.sql importé !<br>";
    
    echo "<br><strong>Base de données créée avec succès !</strong>";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}