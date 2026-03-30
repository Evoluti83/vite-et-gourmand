<section class="menus-header">
    <h1>Nos menus</h1>
    <p class="section-sub">Trouvez le menu parfait pour votre événement</p>
</section>

<section class="menus-filtres" id="filtres">
    <form id="form-filtres" method="GET" action="">
        <input type="hidden" name="page" value="menus">
        <div class="filtres-grid">
            <div class="form-group">
                <label>Prix maximum (€)</label>
                <input type="number" name="prix_max" value="<?= htmlspecialchars($_GET['prix_max'] ?? '') ?>" placeholder="Ex: 500">
            </div>
            <div class="form-group">
                <label>Prix minimum (€)</label>
                <input type="number" name="prix_min" value="<?= htmlspecialchars($_GET['prix_min'] ?? '') ?>" placeholder="Ex: 100">
            </div>
            <div class="form-group">
                <label>Thème</label>
                <select name="theme_id">
                    <option value="">Tous les thèmes</option>
                    <?php foreach ($themes as $t): ?>
                        <option value="<?= $t['theme_id'] ?>" <?= ($_GET['theme_id'] ?? '') == $t['theme_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['libelle']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Régime</label>
                <select name="regime_id">
                    <option value="">Tous les régimes</option>
                    <?php foreach ($regimes as $r): ?>
                        <option value="<?= $r['regime_id'] ?>" <?= ($_GET['regime_id'] ?? '') == $r['regime_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($r['libelle']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Nombre de personnes</label>
                <input type="number" name="nb_pers" value="<?= htmlspecialchars($_GET['nb_pers'] ?? '') ?>" placeholder="Ex: 10">
            </div>
            <div class="form-group filtres-btn">
                <button type="submit" class="btn-primary">Filtrer</button>
                <a href="<?= APP_URL ?>?page=menus" class="btn-outline-dark">Réinitialiser</a>
            </div>
        </div>
    </form>
</section>

<section class="menus-grid-section">
    <?php if (empty($menus)): ?>
        <div class="menus-vide">
            <p>Aucun menu ne correspond à vos critères.</p>
            <a href="<?= APP_URL ?>?page=menus" class="btn-primary">Voir tous les menus</a>
        </div>
    <?php else: ?>
        <div class="menus-grid" id="menus-grid">
            <?php foreach ($menus as $m): ?>
            <div class="menu-card">
                <div class="menu-card-img">
                    <?php if ($m['image']): ?>
                        <img src="<?= APP_URL ?>/<?= htmlspecialchars($m['image']) ?>" alt="<?= htmlspecialchars($m['titre']) ?>">
                    <?php else: ?>
                        <div class="menu-card-img-placeholder">Photo à venir</div>
                    <?php endif; ?>
                    <span class="menu-badge"><?= htmlspecialchars($m['theme']) ?></span>
                </div>
                <div class="menu-card-body">
                    <h2><?= htmlspecialchars($m['titre']) ?></h2>
                    <p class="menu-desc"><?= htmlspecialchars(substr($m['description'], 0, 100)) ?>...</p>
                    <div class="menu-card-meta">
                        <span><?= $m['nb_pers_min'] ?> pers. min.</span>
                        <span class="menu-prix"><?= number_format($m['prix_base'], 2, ',', ' ') ?> €</span>
                    </div>
                    <a href="<?= APP_URL ?>?page=menu-detail&id=<?= $m['menu_id'] ?>" class="btn-primary">Voir le détail</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>