<?php

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header('Location: ' . APP_URL . '?page=menus');
    exit;
}

$pdo = getDB();

$stmt = $pdo->prepare("
    SELECT m.*, t.libelle AS theme, r.libelle AS regime
    FROM menu m
    LEFT JOIN theme t ON m.theme_id = t.theme_id
    LEFT JOIN regime r ON m.regime_id = r.regime_id
    WHERE m.menu_id = :id AND m.actif = 1
");
$stmt->execute(['id' => $id]);
$menu = $stmt->fetch();

if (!$menu) {
    header('Location: ' . APP_URL . '?page=menus');
    exit;
}

$images = $pdo->prepare("
    SELECT * FROM image_menu
    WHERE menu_id = :id
    ORDER BY ordre ASC
");
$images->execute(['id' => $id]);
$images = $images->fetchAll();

$plats = $pdo->prepare("
    SELECT p.*, GROUP_CONCAT(a.libelle SEPARATOR ', ') AS allergenes
    FROM plat p
    JOIN menu_plat mp ON p.plat_id = mp.plat_id
    LEFT JOIN plat_allergene pa ON p.plat_id = pa.plat_id
    LEFT JOIN allergene a ON pa.allergene_id = a.allergene_id
    WHERE mp.menu_id = :id
    GROUP BY p.plat_id
    ORDER BY p.type ASC
");
$plats->execute(['id' => $id]);
$plats = $plats->fetchAll();

$entrees  = array_filter($plats, fn($p) => $p['type'] === 'entree');
$plats_p  = array_filter($plats, fn($p) => $p['type'] === 'plat');
$desserts = array_filter($plats, fn($p) => $p['type'] === 'dessert');

$page_titre = htmlspecialchars($menu['titre']);

require_once __DIR__ . '/../views/layout/header.php';
require_once __DIR__ . '/../views/menu-detail.php';
require_once __DIR__ . '/../views/layout/footer.php';