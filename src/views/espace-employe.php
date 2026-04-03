<section class="espace-section">

    <div class="espace-sidebar">
        <div class="espace-avatar">E</div>
        <div class="espace-nom"><?= htmlspecialchars($_SESSION['user']['prenom']) ?> <?= htmlspecialchars($_SESSION['user']['nom']) ?></div>
        <div class="espace-email"><?= htmlspecialchars($_SESSION['user']['role']) ?></div>
        <nav class="espace-nav">
            <a href="<?= APP_URL ?>?page=espace-employe&action=commandes" <?= $action === 'commandes' ? 'class="active"' : '' ?>>Commandes</a>
            <a href="<?= APP_URL ?>?page=espace-employe&action=avis" <?= $action === 'avis' ? 'class="active"' : '' ?>>Avis à modérer</a>
            <a href="<?= APP_URL ?>?page=espace-employe&action=menus" <?= $action === 'menus' ? 'class="active"' : '' ?>>Gérer les menus</a>
            <a href="<?= APP_URL ?>?page=espace-employe&action=horaires" <?= $action === 'horaires' ? 'class="active"' : '' ?>>Horaires</a>
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
                <input type="hidden" name="page" value="espace-employe">
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
                        <a href="<?= APP_URL ?>?page=espace-employe&action=commandes" class="btn-outline-dark">Reset</a>
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
                            <a href="<?= APP_URL ?>?page=espace-employe&action=detail-commande&id=<?= $c['commande_id'] ?>" class="btn-outline-dark">Gérer</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        <?php elseif ($action === 'detail-commande'): ?>
            <a href="<?= APP_URL ?>?page=espace-employe&action=commandes" class="btn-retour">← Retour</a>
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
                        <form method="POST" action="/vite-et-gourmand/public/index.php?page=espace-employe&action=update-statut">
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
                <p style="color:var(--gris);padding:32px;text-align:center">Aucun avis en attente de modération.</p>
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
                            <form method="POST" action="/vite-et-gourmand/public/index.php?page=espace-employe&action=valider-avis">
                                <input type="hidden" name="avis_id" value="<?= $a['avis_id'] ?>">
                                <input type="hidden" name="decision" value="valide">
                                <button type="submit" class="btn-primary">Valider</button>
                            </form>
                            <form method="POST" action="/vite-et-gourmand/public/index.php?page=espace-employe&action=valider-avis">
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
                        <a href="<?= APP_URL ?>?page=espace-employe&action=edit-menu&id=<?= $m['menu_id'] ?>" class="btn-outline-dark">Modifier</a>
                        <form method="POST" action="/vite-et-gourmand/public/index.php?page=espace-employe&action=toggle-menu">
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

        <?php elseif ($action === 'edit-menu'): ?>
            <a href="<?= APP_URL ?>?page=espace-employe&action=menus" class="btn-retour">← Retour</a>
            <h1>Modifier : <?= htmlspecialchars($menu_edit['titre']) ?></h1>

            <div class="detail-commande-grid">
                <div class="detail-commande-col">
                    <div class="card">
                        <form method="POST" action="/vite-et-gourmand/public/index.php?page=espace-employe&action=save-menu" enctype="multipart/form-data">
                            <input type="hidden" name="menu_id" value="<?= $menu_edit['menu_id'] ?>">
                            <div class="form-group">
                                <label>Titre *</label>
                                <input type="text" name="titre" value="<?= htmlspecialchars($menu_edit['titre']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" rows="4"><?= htmlspecialchars($menu_edit['description']) ?></textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Nb personnes min *</label>
                                    <input type="number" name="nb_pers_min" value="<?= $menu_edit['nb_pers_min'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Prix de base (€) *</label>
                                    <input type="number" step="0.01" name="prix_base" value="<?= $menu_edit['prix_base'] ?>" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Stock</label>
                                    <input type="number" name="stock" value="<?= $menu_edit['stock'] ?>">
                                </div>
                                <div class="form-group">
                                    <label>Thème</label>
                                    <select name="theme_id">
                                        <?php foreach ($themes as $t): ?>
                                            <option value="<?= $t['theme_id'] ?>" <?= $menu_edit['theme_id'] == $t['theme_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($t['libelle']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Régime</label>
                                <select name="regime_id">
                                    <?php foreach ($regimes as $r): ?>
                                        <option value="<?= $r['regime_id'] ?>" <?= $menu_edit['regime_id'] == $r['regime_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($r['libelle']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Conditions</label>
                                <textarea name="conditions" rows="3"><?= htmlspecialchars($menu_edit['conditions']) ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Ajouter des photos</label>
                                <input type="file" name="images[]" multiple accept="image/*">
                                <small>Formats acceptés : JPG, PNG, WEBP</small>
                            </div>
                            <button type="submit" class="btn-primary">Enregistrer</button>
                        </form>
                    </div>
                </div>

                <div class="detail-commande-col">
                    <div class="card">
                        <h3>Galerie d'images</h3>
                        <?php if (empty($images_menu)): ?>
                            <p style="color:var(--gris);font-size:13px">Aucune image pour ce menu.</p>
                        <?php else: ?>
                            <div class="galerie-grid">
                                <?php foreach ($images_menu as $img): ?>
                                <div class="galerie-item">
                                    <img src="<?= APP_URL ?>/<?= htmlspecialchars($img['chemin']) ?>" alt="Image menu">
                                    <form method="POST" action="/vite-et-gourmand/public/index.php?page=espace-employe&action=delete-image">
                                        <input type="hidden" name="image_id" value="<?= $img['image_id'] ?>">
                                        <input type="hidden" name="menu_id" value="<?= $menu_edit['menu_id'] ?>">
                                        <button type="submit" class="btn-danger" style="width:100%;margin-top:4px">Supprimer</button>
                                    </form>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        <?php elseif ($action === 'horaires'): ?>
            <h1>Gestion des horaires</h1>
            <form method="POST" action="/vite-et-gourmand/public/index.php?page=espace-employe&action=update-horaires">
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

        <?php endif; ?>

    </div>
</section>