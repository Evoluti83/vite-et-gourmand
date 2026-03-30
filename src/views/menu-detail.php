<section class="detail-section">

    <div class="detail-header">
        <div class="detail-galerie">
            <?php if (!empty($images)): ?>
                <img src="<?= APP_URL ?>/<?= htmlspecialchars($images[0]['chemin']) ?>" alt="<?= htmlspecialchars($menu['titre']) ?>" class="detail-img-principale">
                <?php if (count($images) > 1): ?>
                <div class="detail-thumbnails">
                    <?php foreach (array_slice($images, 1) as $img): ?>
                        <img src="<?= APP_URL ?>/<?= htmlspecialchars($img['chemin']) ?>" alt="<?= htmlspecialchars($menu['titre']) ?>">
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="detail-img-placeholder">Photo à venir</div>
            <?php endif; ?>
        </div>

        <div class="detail-info">
            <h1><?= htmlspecialchars($menu['titre']) ?></h1>
            <div class="detail-badges">
                <span class="menu-badge"><?= htmlspecialchars($menu['theme']) ?></span>
                <span class="menu-badge-regime"><?= htmlspecialchars($menu['regime']) ?></span>
            </div>
            <div class="detail-meta">
                <span><?= $menu['nb_pers_min'] ?> personnes minimum</span>
                <span class="menu-prix"><?= number_format($menu['prix_base'], 2, ',', ' ') ?> €</span>
            </div>
            <p class="detail-stock">Stock disponible : <?= $menu['stock'] ?> commande(s)</p>
            <p class="detail-desc"><?= htmlspecialchars($menu['description']) ?></p>

            <?php if ($menu['conditions']): ?>
            <div class="detail-conditions">
                <strong>Conditions importantes</strong>
                <p><?= htmlspecialchars($menu['conditions']) ?></p>
                <p>Livraison : 5€ de base + 0,59€/km hors Bordeaux.</p>
                <p>Réduction de 10% pour toute commande de <?= $menu['nb_pers_min'] + 5 ?> personnes ou plus.</p>
            </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'utilisateur'): ?>
                <a href="<?= APP_URL ?>?page=commande&menu_id=<?= $menu['menu_id'] ?>" class="btn-primary btn-commander">Commander ce menu</a>
            <?php elseif (!isset($_SESSION['user'])): ?>
                <a href="<?= APP_URL ?>?page=connexion" class="btn-primary btn-commander">Connectez-vous pour commander</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="detail-plats">
        <h2>Composition du menu</h2>

        <?php if (!empty($entrees)): ?>
        <div class="plats-group">
            <h3>Entrées</h3>
            <?php foreach ($entrees as $p): ?>
            <div class="plat-item">
                <span class="plat-nom"><?= htmlspecialchars($p['nom']) ?></span>
                <?php if ($p['allergenes']): ?>
                    <span class="plat-allergenes">Allergènes : <?= htmlspecialchars($p['allergenes']) ?></span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($plats_p)): ?>
        <div class="plats-group">
            <h3>Plats</h3>
            <?php foreach ($plats_p as $p): ?>
            <div class="plat-item">
                <span class="plat-nom"><?= htmlspecialchars($p['nom']) ?></span>
                <?php if ($p['allergenes']): ?>
                    <span class="plat-allergenes">Allergènes : <?= htmlspecialchars($p['allergenes']) ?></span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($desserts)): ?>
        <div class="plats-group">
            <h3>Desserts</h3>
            <?php foreach ($desserts as $p): ?>
            <div class="plat-item">
                <span class="plat-nom"><?= htmlspecialchars($p['nom']) ?></span>
                <?php if ($p['allergenes']): ?>
                    <span class="plat-allergenes">Allergènes : <?= htmlspecialchars($p['allergenes']) ?></span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

</section>