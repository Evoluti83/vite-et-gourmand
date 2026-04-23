<?php
ob_start();
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/config/autoload.php';
require_once __DIR__ . '/../src/config/db.php';
require_once __DIR__ . '/../src/config/mongodb.php';
require_once __DIR__ . '/../src/config/mail.php';

$route = $_GET['page'] ?? 'accueil';

$base = __DIR__ . '/../src/controllers/';

$routes = [
    'accueil'        => $base . 'AccueilController.php',
    'menus'          => $base . 'MenusController.php',
    'menu-detail'    => $base . 'MenuDetailController.php',
    'contact'        => $base . 'ContactController.php',
    'inscription'    => $base . 'InscriptionController.php',
    'connexion'      => $base . 'ConnexionController.php',
    'deconnexion'    => $base . 'DeconnexionController.php',
    'mot-de-passe'   => $base . 'MotDePasseController.php',
    'commande'       => $base . 'CommandeController.php',
    'espace-user'    => $base . 'EspaceUserController.php',
    'espace-employe' => $base . 'EspaceEmployeController.php',
    'espace-admin'   => $base . 'EspaceAdminController.php',
    'mentions'       => $base . 'MentionsController.php',
    'cgv'            => $base . 'CgvController.php',
];

if (array_key_exists($route, $routes)) {
    require_once $routes[$route];
} else {
    http_response_code(404);
    echo '<h1>Page non trouvée</h1>';
}

ob_end_flush();