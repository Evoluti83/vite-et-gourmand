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

        $subject = "Réinitialisation de votre mot de passe";
        $body = "
        <div style='font-family:sans-serif;max-width:600px;margin:0 auto'>
            <div style='background:#6B2737;padding:24px;text-align:center'>
                <h1 style='color:#C9A84C;margin:0'>Vite &amp; Gourmand</h1>
            </div>
            <div style='padding:32px;background:#FAF7F2'>
                <h2 style='color:#6B2737'>Réinitialisation de mot de passe</h2>
                <p>Vous avez demandé à réinitialiser votre mot de passe.</p>
                <p>Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe :</p>
                <a href='{$lien}' style='background:#6B2737;color:#FAF7F2;padding:12px 24px;text-decoration:none;border-radius:4px;display:inline-block;margin-top:16px'>Réinitialiser mon mot de passe</a>
                <p style='margin-top:24px;color:#888;font-size:13px'>Ce lien est valable 1 heure. Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>
            </div>
            <div style='background:#2C2C2C;padding:16px;text-align:center'>
                <p style='color:#aaa;font-size:12px;margin:0'>Vite &amp; Gourmand — 12 rue des Saveurs, 33000 Bordeaux</p>
            </div>
        </div>";

sendMail($email, $email, $subject, $body);
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