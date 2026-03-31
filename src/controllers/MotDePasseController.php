<?php

$erreurs = [];
$success = false;
$etape = $_GET['etape'] ?? 'demande';
$token = $_GET['token'] ?? '';

if ($etape === 'demande' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'adresse email est invalide.";
    }

    if (empty($erreurs)) {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT utilisateur_id FROM utilisateur WHERE email = :email AND actif = 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $stmt = $pdo->prepare("
                UPDATE utilisateur 
                SET reset_token = :token, reset_expiration = :expiration 
                WHERE email = :email
            ");
            $stmt->execute([
                'token'      => $token,
                'expiration' => $expiration,
                'email'      => $email,
            ]);

            $lien = APP_URL . "?page=mot-de-passe&etape=reset&token=" . $token;
            $success = true;
        } else {
            $success = true;
        }
    }
}

if ($etape === 'reset' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';
    $token    = $_POST['token'] ?? '';

    $regexPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/';
    if (!preg_match($regexPassword, $password)) {
        $erreurs[] = "Le mot de passe ne respecte pas les critères de sécurité.";
    }
    if ($password !== $confirm) {
        $erreurs[] = "Les mots de passe ne correspondent pas.";
    }

    if (empty($erreurs)) {
        $pdo = getDB();
        $stmt = $pdo->prepare("
            SELECT utilisateur_id FROM utilisateur 
            WHERE reset_token = :token 
            AND reset_expiration > NOW()
            AND actif = 1
        ");
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch();

        if ($user) {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("
                UPDATE utilisateur 
                SET password = :password, reset_token = NULL, reset_expiration = NULL
                WHERE utilisateur_id = :id
            ");
            $stmt->execute([
                'password' => $hash,
                'id'       => $user['utilisateur_id'],
            ]);
            $success = true;
        } else {
            $erreurs[] = "Ce lien est invalide ou a expiré. Veuillez faire une nouvelle demande.";
        }
    }
}

$page_titre = 'Réinitialisation du mot de passe';
require_once __DIR__ . '/../views/layout/header.php';
require_once __DIR__ . '/../views/mot-de-passe.php';
require_once __DIR__ . '/../views/layout/footer.php';