<?php

$pdo = getDB();

$avis = $pdo->query("
    SELECT a.note, a.commentaire, a.date_avis, u.nom, u.prenom
    FROM avis a
    JOIN utilisateur u ON a.utilisateur_id = u.utilisateur_id
    WHERE a.statut = 'valide'
    ORDER BY a.date_avis DESC
    LIMIT 6
")->fetchAll();

$page_titre = 'Accueil';

require_once __DIR__ . '/../views/layout/header.php';
require_once __DIR__ . '/../views/accueil.php';
require_once __DIR__ . '/../views/layout/footer.php';