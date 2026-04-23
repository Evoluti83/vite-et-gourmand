<?php

require_once __DIR__ . '/../../src/config/config.php';
require_once __DIR__ . '/../../src/config/db.php';
require_once __DIR__ . '/../../src/config/autoload.php';

header('Content-Type: application/json');

try {
    $pdo            = getDB();
    $menuRepository = new MenuRepository($pdo);
    $menuService    = new MenuService($menuRepository);

    $filtres = [
        'prix_max'  => $_GET['prix_max']  ?? '',
        'prix_min'  => $_GET['prix_min']  ?? '',
        'theme_id'  => $_GET['theme_id']  ?? '',
        'regime_id' => $_GET['regime_id'] ?? '',
        'nb_pers'   => $_GET['nb_pers']   ?? '',
    ];

    $menus = $menuService->getMenusForJson($filtres);

    echo json_encode([
        'success' => true,
        'menus'   => $menus,
        'total'   => count($menus)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Erreur serveur'
    ]);
}