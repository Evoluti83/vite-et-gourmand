<?php

$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'adresse email est invalide.";
    }
    if (empty($password)) {
        $erreurs[] = "Le mot de passe est obligatoire.";
    }

    if (empty($erreurs)) {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT u.*, r.libelle AS role
            FROM utilisateur u
            JOIN role r ON u.role_id = r.role_id
            WHERE u.email = :email
            AND u.actif = 1
        ");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id'     => $user['utilisateur_id'],
                'email'  => $user['email'],
                'nom'    => $user['nom'],
                'prenom' => $user['prenom'],
                'role'   => $user['role'],
            ];

            switch ($user['role']) {
                case 'administrateur':
                    header('Location: ' . APP_URL . '?page=espace-admin');
                    break;
                case 'employe':
                    header('Location: ' . APP_URL . '?page=espace-employe');
                    break;
                default:
                    header('Location: ' . APP_URL . '?page=espace-user');
                    break;
            }
            exit;
        } else {
            $erreurs[] = "Email ou mot de passe incorrect.";
        }
    }
}

$page_titre = 'Connexion';
require_once __DIR__ . '/../views/layout/header.php';
require_once __DIR__ . '/../views/connexion.php';
require_once __DIR__ . '/../views/layout/footer.php';