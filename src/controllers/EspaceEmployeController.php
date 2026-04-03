<?php

if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['employe', 'administrateur'])) {
    header('Location: ' . APP_URL . '?page=connexion');
    exit;
}

$pdo = getDB();
$action = $_GET['action'] ?? 'commandes';
$erreurs = [];
$success = false;

if ($action === 'edit-menu' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $menu_id = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("SELECT * FROM menu WHERE menu_id = :id");
    $stmt->execute(['id' => $menu_id]);
    $menu_edit = $stmt->fetch();
    $images_menu = $pdo->prepare("SELECT * FROM image_menu WHERE menu_id = :id ORDER BY ordre");
    $images_menu->execute(['id' => $menu_id]);
    $images_menu = $images_menu->fetchAll();
    $themes  = $pdo->query("SELECT * FROM theme")->fetchAll();
    $regimes = $pdo->query("SELECT * FROM regime")->fetchAll();
}

if ($action === 'save-menu' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu_id     = (int)($_POST['menu_id'] ?? 0);
    $titre       = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $nb_pers_min = (int)($_POST['nb_pers_min'] ?? 0);
    $prix_base   = (float)($_POST['prix_base'] ?? 0);
    $conditions  = trim($_POST['conditions'] ?? '');
    $stock       = (int)($_POST['stock'] ?? 0);
    $theme_id    = (int)($_POST['theme_id'] ?? 0);
    $regime_id   = (int)($_POST['regime_id'] ?? 0);

    $pdo->prepare("
        UPDATE menu SET titre=:titre, description=:description, nb_pers_min=:nb_pers_min,
        prix_base=:prix_base, conditions=:conditions, stock=:stock,
        theme_id=:theme_id, regime_id=:regime_id
        WHERE menu_id=:id
    ")->execute([
        'titre'       => $titre,
        'description' => $description,
        'nb_pers_min' => $nb_pers_min,
        'prix_base'   => $prix_base,
        'conditions'  => $conditions,
        'stock'       => $stock,
        'theme_id'    => $theme_id,
        'regime_id'   => $regime_id,
        'id'          => $menu_id,
    ]);

    if (!empty($_FILES['images']['name'][0])) {
        $ordre = $pdo->query("SELECT MAX(ordre) AS max_ordre FROM image_menu WHERE menu_id = $menu_id")->fetch()['max_ordre'] ?? 0;
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp) {
            if ($_FILES['images']['error'][$key] === 0) {
                $ext      = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
                $filename = 'menu_' . $menu_id . '_' . time() . '_' . $key . '.' . $ext;
                $dest     = __DIR__ . '/../../public/assets/images/menus/' . $filename;
                if (move_uploaded_file($tmp, $dest)) {
                    $ordre++;
                    $pdo->prepare("INSERT INTO image_menu (menu_id, chemin, ordre) VALUES (:menu_id, :chemin, :ordre)")
                        ->execute(['menu_id' => $menu_id, 'chemin' => 'assets/images/menus/' . $filename, 'ordre' => $ordre]);
                }
            }
        }
    }

    header('Location: ' . APP_URL . '?page=espace-employe&action=menus&success=1');
    exit;
}

if ($action === 'delete-image' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $image_id = (int)($_POST['image_id'] ?? 0);
    $stmt = $pdo->prepare("SELECT * FROM image_menu WHERE image_id = :id");
    $stmt->execute(['id' => $image_id]);
    $img = $stmt->fetch();
    if ($img) {
        $path = __DIR__ . '/../../public/' . $img['chemin'];
        if (file_exists($path)) unlink($path);
        $pdo->prepare("DELETE FROM image_menu WHERE image_id = :id")->execute(['id' => $image_id]);
    }
    header('Location: ' . APP_URL . '?page=espace-employe&action=edit-menu&id=' . ($_POST['menu_id'] ?? 0));
    exit;
}

if ($action === 'update-statut' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $commande_id    = (int)($_POST['commande_id'] ?? 0);
    $nouveau_statut = trim($_POST['statut'] ?? '');
    $commentaire    = trim($_POST['commentaire'] ?? '');

    $statuts_valides = ['accepte', 'en_preparation', 'en_cours_de_livraison', 'livre', 'en_attente_retour_materiel', 'terminee', 'annulee'];

    if ($commande_id && in_array($nouveau_statut, $statuts_valides)) {
        $pdo->prepare("UPDATE commande SET statut_actuel = :statut WHERE commande_id = :id")
            ->execute(['statut' => $nouveau_statut, 'id' => $commande_id]);
        $pdo->prepare("
            INSERT INTO historique_statut (commande_id, statut, commentaire)
            VALUES (:id, :statut, :commentaire)
        ")->execute(['id' => $commande_id, 'statut' => $nouveau_statut, 'commentaire' => $commentaire]);

        $stmt = $pdo->prepare("SELECT u.email, u.prenom FROM utilisateur u JOIN commande c ON c.utilisateur_id = u.utilisateur_id WHERE c.commande_id = :id");
        $stmt->execute(['id' => $commande_id]);
        $client = $stmt->fetch();

        if ($nouveau_statut === 'terminee' && $client) {
            mailInvitationAvis($client['email'], $client['prenom'], (string)$commande_id);
        }
        if ($nouveau_statut === 'en_attente_retour_materiel' && $client) {
            mailRetourMateriel($client['email'], $client['prenom'], (string)$commande_id);
        }
    }
    header('Location: ' . APP_URL . '?page=espace-employe&action=detail-commande&id=' . $commande_id);
    exit;
}

if ($action === 'valider-avis' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $avis_id  = (int)($_POST['avis_id'] ?? 0);
    $decision = $_POST['decision'] ?? '';
    if (in_array($decision, ['valide', 'refuse'])) {
        $pdo->prepare("UPDATE avis SET statut = :statut WHERE avis_id = :id")
            ->execute(['statut' => $decision, 'id' => $avis_id]);
    }
    header('Location: ' . APP_URL . '?page=espace-employe&action=avis');
    exit;
}

if ($action === 'toggle-menu' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu_id = (int)($_POST['menu_id'] ?? 0);
    $actif   = (int)($_POST['actif'] ?? 0);
    $pdo->prepare("UPDATE menu SET actif = :actif WHERE menu_id = :id")
        ->execute(['actif' => $actif ? 0 : 1, 'id' => $menu_id]);
    header('Location: ' . APP_URL . '?page=espace-employe&action=menus');
    exit;
}

if ($action === 'update-horaires' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['horaires'] as $horaire_id => $data) {
        $pdo->prepare("
            UPDATE horaire SET heure_ouverture = :ouverture, heure_fermeture = :fermeture
            WHERE horaire_id = :id
        ")->execute([
            'ouverture' => !empty($data['heure_ouverture']) ? $data['heure_ouverture'] : null,
            'fermeture' => !empty($data['heure_fermeture']) ? $data['heure_fermeture'] : null,
            'id'        => (int)$horaire_id,
        ]);
    }
    header('Location: ' . APP_URL . '?page=espace-employe&action=horaires&success=1');
    exit;
}

if ($action === 'commandes') {
    $where  = ["1=1"];
    $params = [];
    if (!empty($_GET['statut'])) {
        $where[]          = "c.statut_actuel = :statut";
        $params['statut'] = $_GET['statut'];
    }
    if (!empty($_GET['client'])) {
        $where[]          = "(u.nom LIKE :client OR u.prenom LIKE :client OR u.email LIKE :client)";
        $params['client'] = '%' . $_GET['client'] . '%';
    }
    $whereSQL = implode(' AND ', $where);
    $stmt = $pdo->prepare("
        SELECT c.*, m.titre AS menu_titre, u.nom, u.prenom, u.email, u.gsm
        FROM commande c
        JOIN menu m ON c.menu_id = m.menu_id
        JOIN utilisateur u ON c.utilisateur_id = u.utilisateur_id
        WHERE $whereSQL
        ORDER BY c.date_commande DESC
    ");
    $stmt->execute($params);
    $commandes = $stmt->fetchAll();
}

if ($action === 'detail-commande') {
    $commande_id = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("
        SELECT c.*, m.titre AS menu_titre, u.nom, u.prenom, u.email, u.gsm
        FROM commande c
        JOIN menu m ON c.menu_id = m.menu_id
        JOIN utilisateur u ON c.utilisateur_id = u.utilisateur_id
        WHERE c.commande_id = :id
    ");
    $stmt->execute(['id' => $commande_id]);
    $commande = $stmt->fetch();

    if (!$commande) {
        header('Location: ' . APP_URL . '?page=espace-employe');
        exit;
    }

    $historique = $pdo->prepare("
        SELECT * FROM historique_statut 
        WHERE commande_id = :id 
        ORDER BY date_statut ASC
    ");
    $historique->execute(['id' => $commande_id]);
    $historique = $historique->fetchAll();
}

if ($action === 'avis') {
    $avis_liste = $pdo->query("
        SELECT a.*, u.nom, u.prenom, m.titre AS menu_titre
        FROM avis a
        JOIN utilisateur u ON a.utilisateur_id = u.utilisateur_id
        JOIN commande c ON a.commande_id = c.commande_id
        JOIN menu m ON c.menu_id = m.menu_id
        WHERE a.statut = 'en_attente'
        ORDER BY a.date_avis DESC
    ")->fetchAll();
}

if ($action === 'menus') {
    $menus = $pdo->query("
        SELECT m.*, t.libelle AS theme, r.libelle AS regime
        FROM menu m
        LEFT JOIN theme t ON m.theme_id = t.theme_id
        LEFT JOIN regime r ON m.regime_id = r.regime_id
        ORDER BY m.menu_id
    ")->fetchAll();
    $themes  = $pdo->query("SELECT * FROM theme")->fetchAll();
    $regimes = $pdo->query("SELECT * FROM regime")->fetchAll();
}

if ($action === 'horaires') {
    $horaires = $pdo->query("SELECT * FROM horaire ORDER BY horaire_id")->fetchAll();
}

$page_titre = 'Espace employé';
require_once __DIR__ . '/../views/layout/header.php';
require_once __DIR__ . '/../views/espace-employe.php';
require_once __DIR__ . '/../views/layout/footer.php';