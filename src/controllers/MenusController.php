<?php

require_once __DIR__ . '/../repositories/MenuRepository.php';
require_once __DIR__ . '/../services/MenuService.php';

// Guard : page publique — pas de protection nécessaire
$pdo = getDB();
$menuRepository = new MenuRepository($pdo);
$menuService    = new MenuService($menuRepository);

// Filtres depuis GET
$filtres = [
    'prix_max'  => $_GET['prix_max']  ?? '',
    'prix_min'  => $_GET['prix_min']  ?? '',
    'theme_id'  => $_GET['theme_id']  ?? '',
    'regime_id' => $_GET['regime_id'] ?? '',
    'nb_pers'   => $_GET['nb_pers']   ?? '',
];

// Récupération via le service
$menusObjets = $menuService->getMenusFiltres($filtres);

// Conversion en tableaux pour les vues (compatibilité)
$menus = array_map(fn(Menu $m) => $m->toArray(), $menusObjets);

// Données pour les selects de filtres
$themes  = $pdo->query("SELECT * FROM theme ORDER BY libelle")->fetchAll();
$regimes = $pdo->query("SELECT * FROM regime ORDER BY libelle")->fetchAll();

$page_titre = 'Nos menus';
require_once __DIR__ . '/../views/layout/header.php';
require_once __DIR__ . '/../views/menus.php';
require_once __DIR__ . '/../views/layout/footer.php';