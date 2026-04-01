<?php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'utilisateur') {
    header('Location: ' . APP_URL . '?page=connexion');
    exit;
}

$pdo = getDB();
$user_id = $_SESSION['user']['id'];
$action = $_GET['action'] ?? 'commandes';
$erreurs = [];
$success = false;

$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();

if ($action === 'commandes') {
    $commandes = $pdo->prepare("
        SELECT c.*, m.titre AS menu_titre
        FROM commande c
        JOIN menu m ON c.menu_id = m.menu_id
        WHERE c.utilisateur_id = :id
        ORDER BY c.date_commande DESC
    ");
    $commandes->execute(['id' => $user_id]);
    $commandes = $commandes->fetchAll();
}

if ($action === 'detail-commande') {
    $commande_id = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("
        SELECT c.*, m.titre AS menu_titre
        FROM commande c
        JOIN menu m ON c.menu_id = m.menu_id
        WHERE c.commande_id = :id AND c.utilisateur_id = :user_id
    ");
    $stmt->execute(['id' => $commande_id, 'user_id' => $user_id]);
    $commande = $stmt->fetch();

    if (!$commande) {
        header('Location: ' . APP_URL . '?page=espace-user');
        exit;
    }

    $historique = $pdo->prepare("
        SELECT * FROM historique_statut
        WHERE commande_id = :id
        ORDER BY date_statut ASC
    ");
    $historique->execute(['id' => $commande_id]);
    $historique = $historique->fetchAll();

    $avis_existant = $pdo->prepare("
        SELECT * FROM avis WHERE commande_id = :id
    ");
    $avis_existant->execute(['id' => $commande_id]);
    $avis_existant = $avis_existant->fetch();
}

if ($action === 'annuler' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $commande_id = (int)($_POST['commande_id'] ?? 0);
    $stmt = $pdo->prepare("
        SELECT * FROM commande 
        WHERE commande_id = :id AND utilisateur_id = :user_id AND statut_actuel = 'en_attente'
    ");
    $stmt->execute(['id' => $commande_id, 'user_id' => $user_id]);
    $commande = $stmt->fetch();

    if ($commande) {
        $pdo->prepare("UPDATE commande SET statut_actuel = 'annulee' WHERE commande_id = :id")
            ->execute(['id' => $commande_id]);
        $pdo->prepare("
            INSERT INTO historique_statut (commande_id, statut, commentaire)
            VALUES (:id, 'annulee', 'Annulée par le client')
        ")->execute(['id' => $commande_id]);
        $pdo->prepare("UPDATE menu SET stock = stock + 1 WHERE menu_id = :id")
            ->execute(['id' => $commande['menu_id']]);
        $success = true;
    }
    header('Location: ' . APP_URL . '?page=espace-user&action=commandes');
    exit;
}

if ($action === 'avis' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $commande_id = (int)($_POST['commande_id'] ?? 0);
    $note        = (int)($_POST['note'] ?? 0);
    $commentaire = trim($_POST['commentaire'] ?? '');

    if ($note < 1 || $note > 5) $erreurs[] = "La note doit être entre 1 et 5.";
    if (empty($commentaire))     $erreurs[] = "Le commentaire est obligatoire.";

    if (empty($erreurs)) {
        $stmt = $pdo->prepare("
            SELECT * FROM commande 
            WHERE commande_id = :id AND utilisateur_id = :user_id AND statut_actuel = 'terminee'
        ");
        $stmt->execute(['id' => $commande_id, 'user_id' => $user_id]);
        $commande = $stmt->fetch();

        if ($commande) {
            $pdo->prepare("
                INSERT INTO avis (commande_id, utilisateur_id, note, commentaire, statut)
                VALUES (:commande_id, :user_id, :note, :commentaire, 'en_attente')
            ")->execute([
                'commande_id' => $commande_id,
                'user_id'     => $user_id,
                'note'        => $note,
                'commentaire' => $commentaire,
            ]);
            $success = true;
        }
    }
    header('Location: ' . APP_URL . '?page=espace-user&action=detail-commande&id=' . $commande_id);
    exit;
}

if ($action === 'profil' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom     = trim($_POST['nom'] ?? '');
    $prenom  = trim($_POST['prenom'] ?? '');
    $gsm     = trim($_POST['gsm'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $ville   = trim($_POST['ville'] ?? '');

    if (empty($nom))    $erreurs[] = "Le nom est obligatoire.";
    if (empty($prenom)) $erreurs[] = "Le prénom est obligatoire.";

    if (empty($erreurs)) {
        $pdo->prepare("
            UPDATE utilisateur SET nom = :nom, prenom = :prenom, gsm = :gsm, adresse = :adresse, ville = :ville
            WHERE utilisateur_id = :id
        ")->execute([
            'nom'     => $nom,
            'prenom'  => $prenom,
            'gsm'     => $gsm,
            'adresse' => $adresse,
            'ville'   => $ville,
            'id'      => $user_id,
        ]);
        $success = true;
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = :id");
        $stmt->execute(['id' => $user_id]);
        $user = $stmt->fetch();
    }
}

$page_titre = 'Mon espace';
require_once __DIR__ . '/../views/layout/header.php';
require_once __DIR__ . '/../views/espace-user.php';
require_once __DIR__ . '/../views/layout/footer.php';