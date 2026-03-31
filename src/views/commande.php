<section class="commande-section">

    <?php if ($success): ?>
    <div class="commande-success">
        <div class="success-icon">✓</div>
        <h1>Commande confirmée !</h1>
        <p>Votre commande <strong><?= htmlspecialchars($numero_commande) ?></strong> a bien été enregistrée.</p>
        <p>Vous recevrez une confirmation par email.</p>
        <a href="<?= APP_URL ?>?page=espace-user" class="btn-primary">Voir mes commandes</a>
    </div>

    <?php else: ?>

    <h1>Commander un menu</h1>
    <p class="section-sub">Remplissez les informations de votre prestation</p>

    <?php if (!empty($erreurs)): ?>
        <div class="alert alert-error">
            <?php foreach ($erreurs as $e): ?>
                <p><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" id="form-commande">
        <div class="commande-grid">

            <div class="commande-col">
                <h2>Vos informations</h2>
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" value="<?= htmlspecialchars($user['nom']) ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" value="<?= htmlspecialchars($user['prenom']) ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="text" value="<?= htmlspecialchars($user['gsm']) ?>" disabled>
                </div>

                <h2>Livraison</h2>
                <div class="form-group">
                    <label>Adresse de livraison *</label>
                    <input type="text" name="adresse_livraison" value="<?= htmlspecialchars($_POST['adresse_livraison'] ?? $user['adresse']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Ville *</label>
                    <input type="text" name="ville_livraison" id="ville_livraison" value="<?= htmlspecialchars($_POST['ville_livraison'] ?? $user['ville']) ?>" required>
                    <small>Livraison gratuite à Bordeaux. 5€ + 0,59€/km ailleurs.</small>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Date de prestation *</label>
                        <input type="date" name="date_prestation" value="<?= htmlspecialchars($_POST['date_prestation'] ?? '') ?>" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                    </div>
                    <div class="form-group">
                        <label>Heure souhaitée *</label>
                        <input type="time" name="heure_livraison" value="<?= htmlspecialchars($_POST['heure_livraison'] ?? '') ?>" required>
                    </div>
                </div>
            </div>

            <div class="commande-col">
                <h2>Votre menu</h2>
                <div class="form-group">
                    <label>Choisir un menu *</label>
                    <select name="menu_id" id="menu_id" required onchange="updatePrix()">
                        <option value="">-- Sélectionnez un menu --</option>
                        <?php foreach ($menus as $m): ?>
                            <option value="<?= $m['menu_id'] ?>"
                                data-prix="<?= $m['prix_base'] ?>"
                                data-min="<?= $m['nb_pers_min'] ?>"
                                <?= ($menu && $menu['menu_id'] == $m['menu_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['titre']) ?> — <?= $m['nb_pers_min'] ?> pers. min. — <?= number_format($m['prix_base'], 2, ',', ' ') ?> €
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Nombre de personnes *</label>
                    <input type="number" name="nb_personnes" id="nb_personnes" value="<?= htmlspecialchars($_POST['nb_personnes'] ?? '') ?>" required min="1" onchange="updatePrix()">
                    <small id="min-pers-info"></small>
                </div>

                <div class="commande-recap" id="recap">
                    <h3>Récapitulatif du prix</h3>
                    <div class="recap-ligne">
                        <span>Prix du menu</span>
                        <span id="recap-menu">—</span>
                    </div>
                    <div class="recap-ligne" id="recap-remise-ligne" style="display:none">
                        <span>Réduction 10%</span>
                        <span id="recap-remise" class="recap-remise">—</span>
                    </div>
                    <div class="recap-ligne">
                        <span>Frais de livraison</span>
                        <span id="recap-livraison">—</span>
                    </div>
                    <div class="recap-ligne recap-total">
                        <span>Total</span>
                        <span id="recap-total">—</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="commande-submit">
            <button type="submit" class="btn-primary btn-commander">Confirmer la commande</button>
        </div>
    </form>

    <?php endif; ?>

</section>

<script>
function updatePrix() {
    const select = document.getElementById('menu_id');
    const nbPersonnes = parseInt(document.getElementById('nb_personnes').value) || 0;
    const villeInput = document.getElementById('ville_livraison');

    if (!select.value) return;

    const option = select.options[select.selectedIndex];
    const prixBase = parseFloat(option.dataset.prix);
    const nbMin = parseInt(option.dataset.min);

    document.getElementById('min-pers-info').textContent = 'Minimum : ' + nbMin + ' personnes';

    let prixMenu = prixBase;
    let remise = false;

    if (nbPersonnes >= nbMin + 5) {
        remise = true;
        prixMenu = prixBase * 0.9;
    }

    const ville = villeInput.value.toLowerCase().trim();
    const prixLivraison = (ville !== 'bordeaux') ? 5.00 : 0.00;

    const total = prixMenu + prixLivraison;

    document.getElementById('recap-menu').textContent = prixBase.toFixed(2) + ' €';
    document.getElementById('recap-livraison').textContent = prixLivraison === 0 ? 'Gratuit' : prixLivraison.toFixed(2) + ' €';
    document.getElementById('recap-total').textContent = total.toFixed(2) + ' €';

    const remiseLigne = document.getElementById('recap-remise-ligne');
    if (remise) {
        remiseLigne.style.display = 'flex';
        document.getElementById('recap-remise').textContent = '-' + (prixBase * 0.1).toFixed(2) + ' €';
    } else {
        remiseLigne.style.display = 'none';
    }
}

document.getElementById('ville_livraison').addEventListener('input', updatePrix);
</script>