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
    <div id="menus-loader" style="display:none;text-align:center;padding:24px;color:var(--gris)">
    Chargement...
    </div>
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
let debounceTimer = null;

function filtrerMenus() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(fetchMenus, 300);
}

function fetchMenus() {
    const prixMax  = document.getElementById('prix_max').value;
    const prixMin  = document.getElementById('prix_min').value;
    const themeId  = document.getElementById('theme_id').value;
    const regimeId = document.getElementById('regime_id').value;
    const nbPers   = document.getElementById('nb_pers').value;

    const params = new URLSearchParams();
    if (prixMax)  params.append('prix_max', prixMax);
    if (prixMin)  params.append('prix_min', prixMin);
    if (themeId)  params.append('theme_id', themeId);
    if (regimeId) params.append('regime_id', regimeId);
    if (nbPers)   params.append('nb_pers', nbPers);

    const grid = document.getElementById('menus-grid');
    const vide = document.getElementById('menus-vide');
    const loader = document.getElementById('menus-loader');

    grid.style.opacity = '0.4';
    if (loader) loader.style.display = 'block';

    fetch('/api/menus.php?' + params.toString())
        .then(response => {
            if (!response.ok) throw new Error('Erreur réseau');
            return response.json();
        })
        .then(data => {
            if (loader) loader.style.display = 'none';
            grid.style.opacity = '1';

            if (!data.success || data.menus.length === 0) {
                grid.style.display = 'none';
                vide.style.display = 'block';
                return;
            }

            vide.style.display = 'none';
            grid.style.display = 'grid';
            grid.innerHTML = data.menus.map(m => renderCard(m)).join('');
        })
        .catch(error => {
            if (loader) loader.style.display = 'none';
            grid.style.opacity = '1';
            console.error('Erreur Fetch:', error);
        });
}

function renderCard(m) {
    const appUrl = '<?= APP_URL ?>';
    const image = m.image
        ? `<img src="${appUrl}/${m.image}" alt="${escHtml(m.titre)}">`
        : `<div class="menu-card-img-placeholder">Photo à venir</div>`;

    const prix = parseFloat(m.prix_base).toLocaleString('fr-FR', {
        minimumFractionDigits: 2, maximumFractionDigits: 2
    });

    return `
        <div class="menu-card">
            <div class="menu-card-img">
                ${image}
                <span class="menu-badge">${escHtml(m.theme || '')}</span>
            </div>
            <div class="menu-card-body">
                <h2>${escHtml(m.titre)}</h2>
                <p class="menu-desc">${escHtml(m.description.substring(0, 100))}...</p>
                <div class="menu-card-meta">
                    <span>${m.nb_pers_min} pers. min.</span>
                    <span class="menu-prix">${prix} €</span>
                </div>
                <a href="${appUrl}?page=menu-detail&id=${m.menu_id}" class="btn-primary">Voir le détail</a>
            </div>
        </div>`;
}

function escHtml(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str || ''));
    return div.innerHTML;
}

function resetFiltres() {
    document.getElementById('prix_max').value  = '';
    document.getElementById('prix_min').value  = '';
    document.getElementById('theme_id').value  = '';
    document.getElementById('regime_id').value = '';
    document.getElementById('nb_pers').value   = '';
    fetchMenus();
}
</script>