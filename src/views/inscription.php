<section class="auth-section">
    <div class="auth-card">
        <h1>Créer un compte</h1>
        <p class="section-sub">Rejoignez Vite &amp; Gourmand pour commander nos menus</p>

        <?php if ($success): ?>
            <div class="alert alert-success">
                Votre compte a été créé avec succès ! 
                <a href="<?= APP_URL ?>?page=connexion">Se connecter</a>
            </div>
        <?php endif; ?>

        <?php if (!empty($erreurs)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($erreurs as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group">
                    <label>Nom *</label>
                    <input type="text" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Prénom *</label>
                    <input type="text" name="prenom" value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Adresse email *</label>
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Numéro de téléphone *</label>
                <input type="tel" name="gsm" value="<?= htmlspecialchars($_POST['gsm'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Adresse postale *</label>
                <input type="text" name="adresse" value="<?= htmlspecialchars($_POST['adresse'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Ville *</label>
                <input type="text" name="ville" value="<?= htmlspecialchars($_POST['ville'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Mot de passe *</label>
                <input type="password" name="password" required>
                <small>10 caractères minimum, une majuscule, une minuscule, un chiffre, un caractère spécial</small>
            </div>

            <div class="form-group">
                <label>Confirmer le mot de passe *</label>
                <input type="password" name="confirm" required>
            </div>

            <button type="submit" class="btn-primary btn-full">Créer mon compte</button>

            <p class="auth-link">Déjà un compte ? <a href="<?= APP_URL ?>?page=connexion">Se connecter</a></p>
        </form>
        <?php endif; ?>
    </div>
</section>