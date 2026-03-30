<?php
$page_courante = $_GET['page'] ?? 'accueil';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_titre) ? $page_titre . ' — ' . APP_NAME : APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <a href="<?= APP_URL ?>?page=accueil" class="navbar-logo">Vite &amp; Gourmand</a>
    <button class="navbar-toggle" id="navToggle">&#9776;</button>
    <ul class="navbar-links" id="navLinks">
        <li><a href="<?= APP_URL ?>?page=accueil" <?= $page_courante === 'accueil' ? 'class="active"' : '' ?>>Accueil</a></li>
        <li><a href="<?= APP_URL ?>?page=menus" <?= $page_courante === 'menus' ? 'class="active"' : '' ?>>Nos menus</a></li>
        <li><a href="<?= APP_URL ?>?page=contact" <?= $page_courante === 'contact' ? 'class="active"' : '' ?>>Contact</a></li>
        <?php if (isset($_SESSION['user'])): ?>
            <?php $role = $_SESSION['user']['role']; ?>
            <?php if ($role === 'administrateur'): ?>
                <li><a href="<?= APP_URL ?>?page=espace-admin">Mon espace</a></li>
            <?php elseif ($role === 'employe'): ?>
                <li><a href="<?= APP_URL ?>?page=espace-employe">Mon espace</a></li>
            <?php else: ?>
                <li><a href="<?= APP_URL ?>?page=espace-user">Mon espace</a></li>
            <?php endif; ?>
            <li><a href="<?= APP_URL ?>?page=deconnexion" class="btn-nav">Déconnexion</a></li>
        <?php else: ?>
            <li><a href="<?= APP_URL ?>?page=connexion" class="btn-nav">Se connecter</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main>