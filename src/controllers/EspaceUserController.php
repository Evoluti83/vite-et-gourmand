<?php

if (!isset($_SESSION['user'])) {
    header('Location: ' . APP_URL . '?page=connexion');
    exit;
}

$page_titre = 'Mon espace';
require_once __DIR__ . '/../views/layout/header.php';
echo '<section style="padding:48px;text-align:center"><h1>Mon espace</h1><p>En cours de développement...</p></section>';
require_once __DIR__ . '/../views/layout/footer.php';