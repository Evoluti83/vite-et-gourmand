<?php

$pdo = getDB();

$where = ["m.actif = 1"];
$params = [];

if (!empty($_GET['prix_max'])) {
    $where[] = "m.prix_base <= :prix_max";
    $params['prix_max'] = $_GET['prix_max'];
}

if (!empty($_GET['prix_min'])) {
    $where[] = "m.prix_base >= :prix_min";
    $params['prix_min'] = $_GET['prix_min'];
}

if (!empty($_GET['theme_id'])) {
    $where[] = "m.theme_id = :theme_id";
    $params['theme_id'] = $_GET['theme_id'];
}

if (!empty($_GET['regime_id'])) {
    $where[] = "m.regime_id = :regime_id";
    $params['regime_id'] = $_GET['regime_id'];
}

if (!empty($_GET['nb_pers'])) {
    $where[] = "m.nb_pers_min <= :nb_pers";
    $params['nb_pers'] = $_GET['nb_pers'];
}

$whereSQL = implode(' AND ', $where);

$stmt = $pdo->prepare("
    SELECT m.*, t.libelle AS theme, r.libelle AS regime,
           i.chemin AS image
    FROM menu m
    LEFT JOIN theme t ON m.theme_id = t.theme_id
    LEFT JOIN regime r ON m.regime_id = r.regime_id
    LEFT JOIN image_menu i ON i.menu_id = m.menu_id AND i.ordre = 1
    WHERE $whereSQL
    ORDER BY m.menu_id ASC
");
$stmt->execute($params);
$menus = $stmt->fetchAll();

$themes = $pdo->query("SELECT * FROM theme ORDER BY libelle")->fetchAll();
$regimes = $pdo->query("SELECT * FROM regime ORDER BY libelle")->fetchAll();

$page_titre = 'Nos menus';

require_once __DIR__ . '/../views/layout/header.php';
require_once __DIR__ . '/../views/menus.php';
require_once __DIR__ . '/../views/layout/footer.php';