<section class="menus-header">
    <h1>Nos menus</h1>
    <p class="section-sub">Trouvez le menu parfait pour votre événement</p>
</section>

<section class="menus-filtres">
    <div class="filtres-grid">
        <div class="form-group">
            <label>Prix maximum (€)</label>
            <input type="number" id="prix_max" placeholder="Ex: 500" oninput="filtrerMenus()">
        </div>
        <div class="form-group">
            <label>Prix minimum (€)</label>
            <input type="number" id="prix_min" placeholder="Ex: 100" oninput="filtrerMenus()">
        </div>
        <div class="form-group">
            <label>Thème</label>
            <select id="theme_id" onchange="filtrerMenus()">
                <option value="">Tous les thèmes</option>
                <?php foreach ($themes as $t): ?>
                    <option value="<?= $t['theme_id'] ?>"><?= htmlspecialchars($t['libelle']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Régime</label>
            <select id="regime_id" onchange="filtrerMenus()">
                <option value="">Tous les régimes</option>
                <?php foreach ($regimes as $r): ?>
                    <option value="<?= $r['regime_id'] ?>"><?= htmlspecialchars($r['libelle']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Nombre de personnes</label>
            <input type="number" id="nb_pers" placeholder="Ex: 10" oninput="filtrerMenus()">
        </div>
        <div class="form-group filtres-btn">
            <button type="button" onclick="resetFiltres()" class="btn-outline-dark">Réinitialiser</button>
        </div>
    </div>
</section>

<section class="menus-grid-section">
    <div class="menus-grid" id="menus-grid">
        <?php foreach ($menus as $m): ?>
        <div class="menu-card"
             data-prix="<?= $m['prix_base'] ?>"
             data-theme="<?= $m['theme_id'] ?>"
             data-regime="<?= $m['regime_id'] ?>"
             data-pers="<?= $m['nb_pers_min'] ?>">
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
    <div id="menus-vide" style="display:none;text-align:center;padding:48px;color:var(--gris)">
        <p>Aucun menu ne correspond à vos critères.</p>
        <button onclick="resetFiltres()" class="btn-primary" style="margin-top:16px">Voir tous les menus</button>
    </div>
</section>

<script>
function filtrerMenus() {
    const prixMax  = parseFloat(document.getElementById('prix_max').value) || Infinity;
    const prixMin  = parseFloat(document.getElementById('prix_min').value) || 0;
    const themeId  = document.getElementById('theme_id').value;
    const regimeId = document.getElementById('regime_id').value;
    const nbPers   = parseInt(document.getElementById('nb_pers').value) || 0;

    const cards = document.querySelectorAll('.menu-card');
    let visible = 0;

    cards.forEach(card => {
        const prix   = parseFloat(card.dataset.prix);
        const theme  = card.dataset.theme;
        const regime = card.dataset.regime;
        const pers   = parseInt(card.dataset.pers);

        const ok = prix <= prixMax
            && prix >= prixMin
            && (themeId === '' || theme === themeId)
            && (regimeId === '' || regime === regimeId)
            && (nbPers === 0 || pers <= nbPers);

        card.style.display = ok ? 'block' : 'none';
        if (ok) visible++;
    });

    document.getElementById('menus-vide').style.display = visible === 0 ? 'block' : 'none';
    document.getElementById('menus-grid').style.display = visible === 0 ? 'none' : 'grid';
}

function resetFiltres() {
    document.getElementById('prix_max').value  = '';
    document.getElementById('prix_min').value  = '';
    document.getElementById('theme_id').value  = '';
    document.getElementById('regime_id').value = '';
    document.getElementById('nb_pers').value   = '';
    filtrerMenus();
}
</script>