<?php

$erreurs = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre       = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $email       = trim($_POST['email'] ?? '');

    if (empty($titre))       $erreurs[] = "Le titre est obligatoire.";
    if (empty($description)) $erreurs[] = "La description est obligatoire.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'adresse email est invalide.";
    }

    if (empty($erreurs)) {
        $success = true;
    }
}

$page_titre = 'Contact';
require_once __DIR__ . '/../views/layout/header.php';
require_once __DIR__ . '/../views/contact.php';
require_once __DIR__ . '/../views/layout/footer.php';