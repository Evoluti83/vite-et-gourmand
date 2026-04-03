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
        $subject = "Nouveau message de contact : " . $titre;
        $body = "
        <div style='font-family:sans-serif;max-width:600px;margin:0 auto'>
            <div style='background:#6B2737;padding:24px;text-align:center'>
                <h1 style='color:#C9A84C;margin:0'>Vite &amp; Gourmand</h1>
            </div>
            <div style='padding:32px;background:#FAF7F2'>
                <h2 style='color:#6B2737'>Nouveau message de contact</h2>
                <table style='width:100%;border-collapse:collapse'>
                    <tr><td style='padding:8px;border-bottom:1px solid #e0d8d0;color:#888;width:30%'>De</td><td style='padding:8px;border-bottom:1px solid #e0d8d0'>{$email}</td></tr>
                    <tr><td style='padding:8px;border-bottom:1px solid #e0d8d0;color:#888'>Titre</td><td style='padding:8px;border-bottom:1px solid #e0d8d0'>{$titre}</td></tr>
                    <tr><td style='padding:8px;color:#888;vertical-align:top'>Message</td><td style='padding:8px'>{$description}</td></tr>
                </table>
            </div>
            <div style='background:#2C2C2C;padding:16px;text-align:center'>
                <p style='color:#aaa;font-size:12px;margin:0'>Vite &amp; Gourmand — 12 rue des Saveurs, 33000 Bordeaux</p>
            </div>
        </div>";

        sendMail('contact@viteetgourmand.fr', 'Vite & Gourmand', $subject, $body);
        $success = true;
    }
}

$page_titre = 'Contact';
require_once __DIR__ . '/../views/layout/header.php';
require_once __DIR__ . '/../views/contact.php';
require_once __DIR__ . '/../views/layout/footer.php';