<?php

require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/config/db.php';

$route = $_GET['page'] ?? 'accueil';

$routes = [
    'accueil'        => '../src/controllers/AccueilController.php',
    'menus'          => '../src/controllers/MenusController.php',
    'menu-detail'    => '../src/controllers/MenuDetailController.php',
    'contact'        => '../src/controllers/ContactController.php',
    'inscription'    => '../src/controllers/InscriptionController.php',
    'connexion'      => '../src/controllers/ConnexionController.php',
    'deconnexion'    => '../src/controllers/DeconnexionController.php',
    'mot-de-passe'   => '../src/controllers/MotDePasseController.php',
    'commande'       => '../src/controllers/CommandeController.php',
    'espace-user'    => '../src/controllers/EspaceUserController.php',
    'espace-employe' => '../src/controllers/EspaceEmployeController.php',
    'espace-admin'   => '../src/controllers/EspaceAdminController.php',
    'mentions'       => '../src/controllers/MentionsController.php',
    'cgv'            => '../src/controllers/CgvController.php',
];

if (array_key_exists($route, $routes)) {
    require_once $routes[$route];
} else {
    http_response_code(404);
    require_once '../src/controllers/NotFoundController.php';
}