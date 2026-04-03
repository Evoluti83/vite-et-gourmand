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

// Mise à jour des mots de passe
$hash = password_hash('Password1!', PASSWORD_BCRYPT);
$pdo->prepare("UPDATE utilisateur SET password = :hash WHERE email IN ('client@test.fr', 'julie@viteetgourmand.fr', 'jose@viteetgourmand.fr')")
    ->execute(['hash' => $hash]);
echo "<br>Mots de passe mis à jour !";