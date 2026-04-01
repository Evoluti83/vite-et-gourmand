<section class="espace-section">

    <div class="espace-sidebar">
        <div class="espace-avatar"><?= strtoupper(substr($user['prenom'], 0, 1)) ?></div>
        <div class="espace-nom"><?= htmlspecialchars($user['prenom']) ?> <?= htmlspecialchars($user['nom']) ?></div>
        <div class="espace-email"><?= htmlspecialchars($user['email']) ?></div>
        <nav class="espace-nav">
            <a href="<?= APP_URL ?>?page=espace-user&action=commandes" <?= $action === 'commandes' ? 'class="active"' : '' ?>>Mes commandes</a>
            <a href="<?= APP_URL ?>?page=espace-user&action=profil" <?= $action === 'profil' ? 'class="active"' : '' ?>>Mon profil</a>
            <a href="<?= APP_URL ?>?page=deconnexion" class="espace-nav-logout">Déconnexion</a>
        </nav>
    </div>

    <div class="espace-content">

        <?php if ($action === 'commandes'): ?>
            <h1>Mes commandes</h1>
            <?php if (empty($commandes)): ?>
                <div class="espace-vide">
                    <p>Vous n'avez pas encore de commande.</p>
                    <a href="<?= APP_URL ?>?page=menus" class="btn-primary">Découvrir nos menus</a>
                </div>
            <?php else: ?>
                <div class="commandes-liste">
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
                            <p><strong><?= htmlspecialchars($c['menu_titre']) ?></strong></p>
                            <p><?= $c['nb_personnes'] ?> personnes — <?= number_format($c['prix_total'], 2, ',', ' ') ?> €</p>
                            <p>Prestation le <?= date('d/m/Y', strtotime($c['date_prestation'])) ?> à <?= $c['heure_livraison'] ?></p>
                        </div>
                        <div class="commande-item-actions">
                            <a href="<?= APP_URL ?>?page=espace-user&action=detail-commande&id=<?= $c['commande_id'] ?>" class="btn-outline-dark">Voir le détail</a>
                            <?php if ($c['statut_actuel'] === 'en_attente'): ?>
                                <form method="POST" action="<?= APP_URL ?>?page=espace-user&action=annuler" onsubmit="return confirm('Confirmer l\'annulation ?')">
                                    <input type="hidden" name="commande_id" value="<?= $c['commande_id'] ?>">
                                    <button type="submit" class="btn-danger">Annuler</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php elseif ($action === 'detail-commande'): ?>
            <a href="<?= APP_URL ?>?page=espace-user&action=commandes" class="btn-retour">← Retour</a>
            <h1>Commande <?= htmlspecialchars($commande['numero_cmd']) ?></h1>

            <div class="detail-commande-grid">
                <div class="detail-commande-col">
                    <div class="card">
                        <h3>Informations</h3>
                        <table class="info-table">
                            <tr><td>Menu</td><td><?= htmlspecialchars($commande['menu_titre']) ?></td></tr>
                            <tr><td>Personnes</td><td><?= $commande['nb_personnes'] ?></td></tr>
                            <tr><td>Prestation</td><td><?= date('d/m/Y', strtotime($commande['date_prestation'])) ?> à <?= $commande['heure_livraison'] ?></td></tr>
                            <tr><td>Adresse</td><td><?= htmlspecialchars($commande['adresse_livraison']) ?>, <?= htmlspecialchars($commande['ville_livraison']) ?></td></tr>
                            <tr><td>Prix menu</td><td><?= number_format($commande['prix_menu'], 2, ',', ' ') ?> €</td></tr>
                            <tr><td>Livraison</td><td><?= number_format($commande['prix_livraison'], 2, ',', ' ') ?> €</td></tr>
                            <?php if ($commande['remise']): ?>
                            <tr><td>Réduction</td><td class="text-success">-10%</td></tr>
                            <?php endif; ?>
                            <tr class="total-row"><td>Total</td><td><?= number_format($commande['prix_total'], 2, ',', ' ') ?> €</td></tr>
                        </table>
                    </div>
                </div>

                <div class="detail-commande-col">
                    <?php if (!empty($historique)): ?>
                    <div class="card">
                        <h3>Suivi de commande</h3>
                        <div class="historique-liste">
                            <?php foreach ($historique as $h): ?>
                            <div class="historique-item">
                                <div class="historique-statut statut-<?= $h['statut'] ?>">
                                    <?= ucfirst(str_replace('_', ' ', $h['statut'])) ?>
                                </div>
                                <div class="historique-date">
                                    <?= date('d/m/Y à H:i', strtotime($h['date_statut'])) ?>
                                </div>
                                <?php if ($h['commentaire']): ?>
                                <div class="historique-commentaire"><?= htmlspecialchars($h['commentaire']) ?></div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($commande['statut_actuel'] === 'terminee' && !$avis_existant): ?>
                    <div class="card" style="margin-top:16px">
                        <h3>Donner mon avis</h3>
                        <?php if (!empty($erreurs)): ?>
                            <div class="alert alert-error">
                                <?php foreach ($erreurs as $e): ?>
                                    <p><?= htmlspecialchars($e) ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="<?= APP_URL ?>?page=espace-user&action=avis">
                            <input type="hidden" name="commande_id" value="<?= $commande['commande_id'] ?>">
                            <div class="form-group">
                                <label>Note *</label>
                                <div class="stars-input">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <input type="radio" name="note" id="note<?= $i ?>" value="<?= $i ?>">
                                        <label for="note<?= $i ?>">★</label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Commentaire *</label>
                                <textarea name="commentaire" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn-primary">Envoyer mon avis</button>
                        </form>
                    </div>
                    <?php elseif ($avis_existant): ?>
                    <div class="card" style="margin-top:16px">
                        <h3>Votre avis</h3>
                        <div class="stars"><?= str_repeat('★', $avis_existant['note']) ?></div>
                        <p><?= htmlspecialchars($avis_existant['commentaire']) ?></p>
                        <span class="statut-badge statut-<?= $avis_existant['statut'] ?>"><?= ucfirst($avis_existant['statut']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        <?php elseif ($action === 'profil'): ?>
            <h1>Mon profil</h1>
            <?php if ($success): ?>
                <div class="alert alert-success">Profil mis à jour avec succès !</div>
            <?php endif; ?>
            <?php if (!empty($erreurs)): ?>
                <div class="alert alert-error">
                    <?php foreach ($erreurs as $e): ?><p><?= htmlspecialchars($e) ?></p><?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="auth-card" style="max-width:600px">
                <form method="POST" action="<?= APP_URL ?>?page=espace-user&action=profil">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nom *</label>
                            <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Prénom *</label>
                            <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="tel" name="gsm" value="<?= htmlspecialchars($user['gsm']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Adresse</label>
                        <input type="text" name="adresse" value="<?= htmlspecialchars($user['adresse']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Ville</label>
                        <input type="text" name="ville" value="<?= htmlspecialchars($user['ville']) ?>">
                    </div>
                    <button type="submit" class="btn-primary btn-full">Mettre à jour</button>
                </form>
            </div>

        <?php endif; ?>

    </div>
</section>