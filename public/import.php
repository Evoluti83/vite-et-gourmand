<?php
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/config/db.php';

try {
    $pdo = getDB();
    
    // Insertion des images
    $pdo->exec("DELETE FROM image_menu");
    $pdo->exec("
        INSERT INTO image_menu (image_id, menu_id, chemin, ordre) VALUES
        (6, 1, 'assets/images/menus/menu_1_1775206297_0.jpg', 3),
        (7, 1, 'assets/images/menus/menu_1_1775206384_0.jpg', 4),
        (8, 2, 'assets/images/menus/menu_2_1775206459_0.jpg', 2),
        (9, 2, 'assets/images/menus/menu_2_1775206469_0.jpg', 3),
        (10, 3, 'assets/images/menus/menu_3_1775206483_0.jpg', 1),
        (11, 3, 'assets/images/menus/menu_3_1775206490_0.jpg', 2),
        (12, 4, 'assets/images/menus/menu_4_1775206559_0.jpg', 1),
        (13, 4, 'assets/images/menus/menu_4_1775206565_0.jpg', 2)
    ");
    echo "Images importées avec succès !";
    
    // Mise à jour des mots de passe
    $hash = password_hash('Password1!', PASSWORD_BCRYPT);
    $pdo->prepare("UPDATE utilisateur SET password = :hash WHERE email IN ('client@test.fr', 'julie@viteetgourmand.fr', 'jose@viteetgourmand.fr')")
        ->execute(['hash' => $hash]);
    echo "<br>Mots de passe mis à jour !";

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}