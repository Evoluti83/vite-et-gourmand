<?php

$erreurs = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom      = trim($_POST['nom'] ?? '');
    $prenom   = trim($_POST['prenom'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $gsm      = trim($_POST['gsm'] ?? '');
    $adresse  = trim($_POST['adresse'] ?? '');
    $ville    = trim($_POST['ville'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if (empty($nom))     $erreurs[] = "Le nom est obligatoire.";
    if (empty($prenom))  $erreurs[] = "Le prénom est obligatoire.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'adresse email est invalide.";
    }
    if (empty($gsm))     $erreurs[] = "Le numéro de téléphone est obligatoire.";
    if (empty($adresse)) $erreurs[] = "L'adresse postale est obligatoire.";
    if (empty($ville))   $erreurs[] = "La ville est obligatoire.";

    $regexPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/';
    if (!preg_match($regexPassword, $password)) {
        $erreurs[] = "Le mot de passe doit contenir au minimum 10 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
    }
    if ($password !== $confirm) {
        $erreurs[] = "Les mots de passe ne correspondent pas.";
    }

    if (empty($erreurs)) {
        $pdo = getDB();

        $stmt = $pdo->prepare("SELECT utilisateur_id FROM utilisateur WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $erreurs[] = "Cette adresse email est déjà utilisée.";
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("
                INSERT INTO utilisateur (email, password, nom, prenom, gsm, adresse, ville, role_id)
                VALUES (:email, :password, :nom, :prenom, :gsm, :adresse, :ville, 3)
            ");
            $stmt->execute([
                'email'    => $email,
                'password' => $hash,
                'nom'      => $nom,
                'prenom'   => $prenom,
                'gsm'      => $gsm,
                'adresse'  => $adresse,
                'ville'    => $ville,
            ]);

            $success = true;
        }
    }
}

$page_titre = 'Créer un compte';
require_once __DIR__ . '/../views/layout/header.php';
require_once __DIR__ . '/../views/inscription.php';
require_once __DIR__ . '/../views/layout/footer.php';