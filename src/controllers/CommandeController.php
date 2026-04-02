<?php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'utilisateur') {
    header('Location: ' . APP_URL . '?page=connexion');
    exit;
}

$pdo = getDB();
require_once __DIR__ . '/../config/mongodb.php';
$erreurs = [];
$success = false;

$menu_id = isset($_GET['menu_id']) ? (int)$_GET['menu_id'] : 0;
if (isset($_POST['menu_id'])) {
    $menu_id = (int)$_POST['menu_id'];
}

$menu = null;
if ($menu_id) {
    $stmt = $pdo->prepare("
        SELECT m.*, t.libelle AS theme, r.libelle AS regime
        FROM menu m
        LEFT JOIN theme t ON m.theme_id = t.theme_id
        LEFT JOIN regime r ON m.regime_id = r.regime_id
        WHERE m.menu_id = :id AND m.actif = 1 AND m.stock > 0
    ");
    $stmt->execute(['id' => $menu_id]);
    $menu = $stmt->fetch();
}

$menus = $pdo->query("SELECT menu_id, titre, nb_pers_min, prix_base FROM menu WHERE actif = 1 AND stock > 0")->fetchAll();

$user_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();

$prix_menu     = 0;
$prix_livraison = 0;
$remise        = false;
$prix_total    = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu_id          = (int)($_POST['menu_id'] ?? 0);
    $nb_personnes     = (int)($_POST['nb_personnes'] ?? 0);
    $adresse_livraison = trim($_POST['adresse_livraison'] ?? '');
    $ville_livraison  = trim($_POST['ville_livraison'] ?? '');
    $date_prestation  = trim($_POST['date_prestation'] ?? '');
    $heure_livraison  = trim($_POST['heure_livraison'] ?? '');

    if (!$menu_id)              $erreurs[] = "Veuillez choisir un menu.";
    if ($nb_personnes <= 0)     $erreurs[] = "Le nombre de personnes est invalide.";
    if (empty($adresse_livraison)) $erreurs[] = "L'adresse de livraison est obligatoire.";
    if (empty($ville_livraison))   $erreurs[] = "La ville de livraison est obligatoire.";
    if (empty($date_prestation))   $erreurs[] = "La date de prestation est obligatoire.";
    if (empty($heure_livraison))   $erreurs[] = "L'heure de livraison est obligatoire.";

    if (empty($erreurs) && $menu) {
        if ($nb_personnes < $menu['nb_pers_min']) {
            $erreurs[] = "Le nombre minimum de personnes pour ce menu est " . $menu['nb_pers_min'] . ".";
        }
    }

    if (empty($erreurs)) {
        $stmt = $pdo->prepare("SELECT * FROM menu WHERE menu_id = :id AND actif = 1 AND stock > 0");
        $stmt->execute(['id' => $menu_id]);
        $menu = $stmt->fetch();

        $prix_menu = $menu['prix_base'];

        if ($nb_personnes >= ($menu['nb_pers_min'] + REMISE_PERSONNES)) {
            $remise = true;
            $prix_menu = $prix_menu * (1 - REMISE_TAUX);
        }

        $ville_lower = strtolower(trim($ville_livraison));
        if ($ville_lower !== 'bordeaux') {
            $prix_livraison = LIVRAISON_BASE;
        }

        $prix_total = $prix_menu + $prix_livraison;

        $numero_cmd = 'CMD-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(4)));

        $stmt = $pdo->prepare("
            INSERT INTO commande (
                numero_cmd, utilisateur_id, menu_id,
                date_prestation, heure_livraison,
                adresse_livraison, ville_livraison,
                nb_personnes, prix_menu, prix_livraison,
                remise, prix_total, statut_actuel
            ) VALUES (
                :numero_cmd, :utilisateur_id, :menu_id,
                :date_prestation, :heure_livraison,
                :adresse_livraison, :ville_livraison,
                :nb_personnes, :prix_menu, :prix_livraison,
                :remise, :prix_total, 'en_attente'
            )
        ");
        $stmt->execute([
            'numero_cmd'       => $numero_cmd,
            'utilisateur_id'   => $user_id,
            'menu_id'          => $menu_id,
            'date_prestation'  => $date_prestation,
            'heure_livraison'  => $heure_livraison,
            'adresse_livraison' => $adresse_livraison,
            'ville_livraison'  => $ville_livraison,
            'nb_personnes'     => $nb_personnes,
            'prix_menu'        => $prix_menu,
            'prix_livraison'   => $prix_livraison,
            'remise'           => $remise ? 1 : 0,
            'prix_total'       => $prix_total,
        ]);

        $commande_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("
            INSERT INTO historique_statut (commande_id, statut, commentaire)
            VALUES (:commande_id, 'en_attente', 'Commande créée')
        ");
        $stmt->execute(['commande_id' => $commande_id]);

        $mongo = getMongoDB();
        $mongo->commandes_stats->insertOne([
            'commande_id'     => (int)$commande_id,
            'menu_id'         => (int)$menu_id,
            'menu_titre'      => $menu['titre'],
            'date_commande'   => new MongoDB\BSON\UTCDateTime(),
            'date_prestation' => $date_prestation,
            'nb_personnes'    => (int)$nb_personnes,
            'prix_menu'       => (float)$prix_menu,
            'prix_livraison'  => (float)$prix_livraison,
            'remise'          => $remise,
            'prix_total'      => (float)$prix_total,
            'statut'          => 'en_attente',
            'ville_livraison' => $ville_livraison,
            ]);

        $stmt = $pdo->prepare("UPDATE menu SET stock = stock - 1 WHERE menu_id = :id");
        $stmt->execute(['id' => $menu_id]);

        $success = true;
        $numero_commande = $numero_cmd;
    }
}

$page_titre = 'Commander un menu';
require_once __DIR__ . '/../views/layout/header.php';
require_once __DIR__ . '/../views/commande.php';
require_once __DIR__ . '/../views/layout/footer.php';