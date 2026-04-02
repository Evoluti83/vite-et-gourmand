<section class="espace-section">

    <div class="espace-sidebar">
        <div class="espace-avatar">A</div>
        <div class="espace-nom"><?= htmlspecialchars($_SESSION['user']['prenom']) ?> <?= htmlspecialchars($_SESSION['user']['nom']) ?></div>
        <div class="espace-email" style="color:var(--or)">Administrateur</div>
        <nav class="espace-nav">
            <a href="<?= APP_URL ?>?page=espace-admin&action=commandes" <?= $action === 'commandes' ? 'class="active"' : '' ?>>Commandes</a>
            <a href="<?= APP_URL ?>?page=espace-admin&action=avis" <?= $action === 'avis' ? 'class="active"' : '' ?>>Avis à modérer</a>
            <a href="<?= APP_URL ?>?page=espace-admin&action=menus" <?= $action === 'menus' ? 'class="active"' : '' ?>>Gérer les menus</a>
            <a href="<?= APP_URL ?>?page=espace-admin&action=horaires" <?= $action === 'horaires' ? 'class="active"' : '' ?>>Horaires</a>
            <a href="<?= APP_URL ?>?page=espace-admin&action=employes" <?= $action === 'employes' ? 'class="active"' : '' ?>>Employés</a>
            <a href="<?= APP_URL ?>?page=espace-admin&action=stats" <?= $action === 'stats' ? 'class="active"' : '' ?>>Statistiques</a>
            <a href="<?= APP_URL ?>?page=deconnexion" class="espace-nav-logout">Déconnexion</a>
        </nav>
    </div>

    <div class="espace-content">

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Modification enregistrée avec succès !</div>
        <?php endif; ?>

        <?php if ($action === 'commandes'): ?>
            <h1>Gestion des commandes</h1>
            <form method="GET" action="" class="employe-filtres">
                <input type="hidden" name="page" value="espace-admin">
                <input type="hidden" name="action" value="commandes">
                <div class="filtres-grid">
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="statut">
                            <option value="">Tous les statuts</option>
                            <option value="en_attente" <?= ($_GET['statut'] ?? '') === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                            <option value="accepte" <?= ($_GET['statut'] ?? '') === 'accepte' ? 'selected' : '' ?>>Accepté</option>
                            <option value="en_preparation" <?= ($_GET['statut'] ?? '') === 'en_preparation' ? 'selected' : '' ?>>En préparation</option>
                            <option value="en_cours_de_livraison" <?= ($_GET['statut'] ?? '') === 'en_cours_de_livraison' ? 'selected' : '' ?>>En cours de livraison</option>
                            <option value="livre" <?= ($_GET['statut'] ?? '') === 'livre' ? 'selected' : '' ?>>Livré</option>
                            <option value="terminee" <?= ($_GET['statut'] ?? '') === 'terminee' ? 'selected' : '' ?>>Terminée</option>
                            <option value="annulee" <?= ($_GET['statut'] ?? '') === 'annulee' ? 'selected' : '' ?>>Annulée</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Client</label>
                        <input type="text" name="client" value="<?= htmlspecialchars($_GET['client'] ?? '') ?>" placeholder="Nom, prénom ou email">
                    </div>
                    <div class="form-group filtres-btn">
                        <button type="submit" class="btn-primary">Filtrer</button>
                        <a href="<?= APP_URL ?>?page=espace-admin&action=commandes" class="btn-outline-dark">Reset</a>
                    </div>
                </div>
            </form>
            <div class="commandes-liste">
                <?php if (empty($commandes)): ?>
                    <p style="color:var(--gris);text-align:center;padding:32px">Aucune commande trouvée.</p>
                <?php else: ?>
                    <?php foreach ($commandes as $c): ?>
                    <div class="commande-item">
                        <div class="commande-item-header">
                            <div>
                                <span class="commande-ref"><?= htmlspecialchars($c['numero_cmd']) ?></span>
                                <span class="commande-date"><?= date('d/m/Y', strtotime($c['date_commande'])) ?></span>
                            </div>
                            <span class="statut-badge statut-<?= $c['statut_actuel'] ?>">
                                <?= ucfirst(str_replace('_', ' ', $c['statut_actuel'])) ?>
                            </span>
                        </div>
                        <div class="commande-item-body">
                            <p><strong><?= htmlspecialchars($c['menu_titre']) ?></strong> — <?= $c['nb_personnes'] ?> personnes</p>
                            <p>Client : <?= htmlspecialchars($c['prenom']) ?> <?= htmlspecialchars($c['nom']) ?> — <?= htmlspecialchars($c['email']) ?></p>
                            <p>Prestation le <?= date('d/m/Y', strtotime($c['date_prestation'])) ?> à <?= $c['heure_livraison'] ?></p>
                            <p>Total : <strong><?= number_format($c['prix_total'], 2, ',', ' ') ?> €</strong></p>
                        </div>
                        <div class="commande-item-actions">
                            <a href="<?= APP_URL ?>?page=espace-admin&action=detail-commande&id=<?= $c['commande_id'] ?>" class="btn-outline-dark">Gérer</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        <?php elseif ($action === 'detail-commande'): ?>
            <a href="<?= APP_URL ?>?page=espace-admin&action=commandes" class="btn-retour">← Retour</a>
            <h1>Commande <?= htmlspecialchars($commande['numero_cmd']) ?></h1>
            <div class="detail-commande-grid">
                <div class="detail-commande-col">
                    <div class="card">
                        <h3>Informations client</h3>
                        <table class="info-table">
                            <tr><td>Client</td><td><?= htmlspecialchars($commande['prenom']) ?> <?= htmlspecialchars($commande['nom']) ?></td></tr>
                            <tr><td>Email</td><td><?= htmlspecialchars($commande['email']) ?></td></tr>
                            <tr><td>GSM</td><td><?= htmlspecialchars($commande['gsm']) ?></td></tr>
                            <tr><td>Menu</td><td><?= htmlspecialchars($commande['menu_titre']) ?></td></tr>
                            <tr><td>Personnes</td><td><?= $commande['nb_personnes'] ?></td></tr>
                            <tr><td>Prestation</td><td><?= date('d/m/Y', strtotime($commande['date_prestation'])) ?> à <?= $commande['heure_livraison'] ?></td></tr>
                            <tr><td>Adresse</td><td><?= htmlspecialchars($commande['adresse_livraison']) ?>, <?= htmlspecialchars($commande['ville_livraison']) ?></td></tr>
                            <tr class="total-row"><td>Total</td><td><?= number_format($commande['prix_total'], 2, ',', ' ') ?> €</td></tr>
                        </table>
                    </div>
                    <div class="card" style="margin-top:16px">
                        <h3>Mettre à jour le statut</h3>
                        <form method="POST" action="/vite-et-gourmand/public/index.php?page=espace-admin&action=update-statut">
                            <input type="hidden" name="commande_id" value="<?= $commande['commande_id'] ?>">
                            <div class="form-group">
                                <label>Nouveau statut</label>
                                <select name="statut">
                                    <option value="accepte">Accepté</option>
                                    <option value="en_preparation">En préparation</option>
                                    <option value="en_cours_de_livraison">En cours de livraison</option>
                                    <option value="livre">Livré</option>
                                    <option value="en_attente_retour_materiel">En attente retour matériel</option>
                                    <option value="terminee">Terminée</option>
                                    <option value="annulee">Annulée</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Commentaire</label>
                                <input type="text" name="commentaire" placeholder="Motif, remarque...">
                            </div>
                            <button type="submit" class="btn-primary">Mettre à jour</button>
                        </form>
                    </div>
                </div>
                <div class="detail-commande-col">
                    <div class="card">
                        <h3>Historique</h3>
                        <div class="historique-liste">
                            <?php foreach ($historique as $h): ?>
                            <div class="historique-item">
                                <div class="historique-statut"><?= ucfirst(str_replace('_', ' ', $h['statut'])) ?></div>
                                <div class="historique-date"><?= date('d/m/Y à H:i', strtotime($h['date_statut'])) ?></div>
                                <?php if ($h['commentaire']): ?>
                                    <div class="historique-commentaire"><?= htmlspecialchars($h['commentaire']) ?></div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ($action === 'avis'): ?>
            <h1>Avis à modérer</h1>
            <?php if (empty($avis_liste)): ?>
                <p style="color:var(--gris);padding:32px;text-align:center">Aucun avis en attente.</p>
            <?php else: ?>
                <div class="avis-moderation">
                    <?php foreach ($avis_liste as $a): ?>
                    <div class="card avis-card-employe">
                        <div class="avis-card-header">
                            <div>
                                <span class="stars"><?= str_repeat('★', $a['note']) ?><?= str_repeat('☆', 5 - $a['note']) ?></span>
                                <span style="font-size:12px;color:var(--gris);margin-left:8px"><?= date('d/m/Y', strtotime($a['date_avis'])) ?></span>
                            </div>
                            <span style="font-size:13px;color:var(--gris)"><?= htmlspecialchars($a['prenom']) ?> <?= htmlspecialchars($a['nom']) ?></span>
                        </div>
                        <p class="avis-text">"<?= htmlspecialchars($a['commentaire']) ?>"</p>
                        <p style="font-size:12px;color:var(--gris);margin-bottom:12px">Menu : <?= htmlspecialchars($a['menu_titre']) ?></p>
                        <div style="display:flex;gap:8px">
                            <form method="POST" action="/vite-et-gourmand/public/index.php?page=espace-admin&action=valider-avis">
                                <input type="hidden" name="avis_id" value="<?= $a['avis_id'] ?>">
                                <input type="hidden" name="decision" value="valide">
                                <button type="submit" class="btn-primary">Valider</button>
                            </form>
                            <form method="POST" action="/vite-et-gourmand/public/index.php?page=espace-admin&action=valider-avis">
                                <input type="hidden" name="avis_id" value="<?= $a['avis_id'] ?>">
                                <input type="hidden" name="decision" value="refuse">
                                <button type="submit" class="btn-danger">Refuser</button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php elseif ($action === 'menus'): ?>
            <h1>Gestion des menus</h1>
            <div class="commandes-liste">
                <?php foreach ($menus as $m): ?>
                <div class="commande-item">
                    <div class="commande-item-header">
                        <div>
                            <span class="commande-ref"><?= htmlspecialchars($m['titre']) ?></span>
                            <span class="commande-date"><?= htmlspecialchars($m['theme']) ?> — <?= htmlspecialchars($m['regime']) ?></span>
                        </div>
                        <span class="statut-badge <?= $m['actif'] ? 'statut-terminee' : 'statut-annulee' ?>">
                            <?= $m['actif'] ? 'Actif' : 'Inactif' ?>
                        </span>
                    </div>
                    <div class="commande-item-body">
                        <p><?= $m['nb_pers_min'] ?> personnes min. — <?= number_format($m['prix_base'], 2, ',', ' ') ?> € — Stock : <?= $m['stock'] ?></p>
                    </div>
                    <div class="commande-item-actions">
                        <form method="POST" action="/vite-et-gourmand/public/index.php?page=espace-admin&action=toggle-menu">
                            <input type="hidden" name="menu_id" value="<?= $m['menu_id'] ?>">
                            <input type="hidden" name="actif" value="<?= $m['actif'] ?>">
                            <button type="submit" class="<?= $m['actif'] ? 'btn-danger' : 'btn-primary' ?>">
                                <?= $m['actif'] ? 'Désactiver' : 'Activer' ?>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($action === 'horaires'): ?>
            <h1>Gestion des horaires</h1>
            <form method="POST" action="/vite-et-gourmand/public/index.php?page=espace-admin&action=update-horaires">
                <div class="horaires-grid">
                    <?php foreach ($horaires as $h): ?>
                    <div class="horaire-item">
                        <div class="horaire-jour"><?= ucfirst($h['jour']) ?></div>
                        <div class="form-group">
                            <label>Ouverture</label>
                            <input type="time" name="horaires[<?= $h['horaire_id'] ?>][heure_ouverture]" value="<?= $h['heure_ouverture'] ?>">
                        </div>
                        <div class="form-group">
                            <label>Fermeture</label>
                            <input type="time" name="horaires[<?= $h['horaire_id'] ?>][heure_fermeture]" value="<?= $h['heure_fermeture'] ?>">
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="btn-primary" style="margin-top:24px">Enregistrer les horaires</button>
            </form>

        <?php elseif ($action === 'employes'): ?>
            <h1>Gestion des employés</h1>
            <?php if ($success): ?>
                <div class="alert alert-success">Compte employé créé avec succès !</div>
            <?php endif; ?>
            <?php if (!empty($erreurs)): ?>
                <div class="alert alert-error">
                    <?php foreach ($erreurs as $e): ?><p><?= htmlspecialchars($e) ?></p><?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="admin-grid">
                <div>
                    <h2 style="font-size:18px;margin-bottom:16px">Créer un compte employé</h2>
                    <div class="card">
                        <form method="POST" action="/vite-et-gourmand/public/index.php?page=espace-admin&action=creer-employe">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Nom *</label>
                                    <input type="text" name="nom" required>
                                </div>
                                <div class="form-group">
                                    <label>Prénom *</label>
                                    <input type="text" name="prenom" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label>Mot de passe *</label>
                                <input type="password" name="password" required>
                                <small>Ce mot de passe ne sera pas communiqué par mail à l'employé.</small>
                            </div>
                            <button type="submit" class="btn-primary">Créer le compte</button>
                        </form>
                    </div>
                </div>
                <div>
                    <h2 style="font-size:18px;margin-bottom:16px">Liste des employés</h2>
                    <?php foreach ($employes as $e): ?>
                    <div class="commande-item">
                        <div class="commande-item-header">
                            <div>
                                <span class="commande-ref"><?= htmlspecialchars($e['prenom']) ?> <?= htmlspecialchars($e['nom']) ?></span>
                                <span class="commande-date"><?= htmlspecialchars($e['email']) ?></span>
                            </div>
                            <span class="statut-badge <?= $e['actif'] ? 'statut-terminee' : 'statut-annulee' ?>">
                                <?= $e['actif'] ? 'Actif' : 'Désactivé' ?>
                            </span>
                        </div>
                        <div class="commande-item-actions">
                            <form method="POST" action="/vite-et-gourmand/public/index.php?page=espace-admin&action=toggle-employe">
                                <input type="hidden" name="user_id" value="<?= $e['utilisateur_id'] ?>">
                                <input type="hidden" name="actif" value="<?= $e['actif'] ?>">
                                <button type="submit" class="<?= $e['actif'] ? 'btn-danger' : 'btn-primary' ?>">
                                    <?= $e['actif'] ? 'Désactiver' : 'Activer' ?>
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        <?php elseif ($action === 'stats'): ?>
            <h1>Statistiques</h1>
            <div class="stats-cards">
                <?php foreach ($stats as $s): ?>
                <div class="stat-card">
                    <div class="stat-num"><?= $s['nb_commandes'] ?></div>
                    <div class="stat-lbl"><?= htmlspecialchars($s['menu_titre']) ?></div>
                    <div style="font-size:13px;color:var(--bordeaux);font-weight:700;margin-top:4px">
                        <?= number_format($s['ca_total'], 2, ',', ' ') ?> €
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="card" style="margin-top:32px">
                <canvas id="chartCommandes" height="100"></canvas>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
            <script>
            const ctx = document.getElementById('chartCommandes').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [<?php foreach ($stats as $s): ?>'<?= addslashes($s['menu_titre']) ?>',<?php endforeach; ?>],
                    datasets: [{
                        label: 'Nombre de commandes',
                        data: [<?php foreach ($stats as $s): ?><?= $s['nb_commandes'] ?>,<?php endforeach; ?>],
                        backgroundColor: '#6B2737',
                        borderRadius: 4,
                    }, {
                        label: 'CA (€)',
                        data: [<?php foreach ($stats as $s): ?><?= $s['ca_total'] ?>,<?php endforeach; ?>],
                        backgroundColor: '#C9A84C',
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Commandes et CA par menu' }
                    }
                }
            });
            </script>

        <?php else: ?>
            <p style="color:var(--gris);padding:32px">Sélectionnez une section dans le menu.</p>

        <?php endif; ?>

    </div>
</section>

<style>
.admin-grid {
    display: flex;
    gap: 32px;
}
.admin-grid > div {
    flex: 1;
}
.stats-cards {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 16px;
}
</style>