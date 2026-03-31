<section class="auth-section">
    <div class="auth-card">
        <h1>Se connecter</h1>
        <p class="section-sub">Accédez à votre espace personnel</p>

        <?php if (!empty($erreurs)): ?>
            <div class="alert alert-error">
                <?php foreach ($erreurs as $e): ?>
                    <p><?= htmlspecialchars($e) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Adresse email *</label>
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autofocus>
            </div>

            <div class="form-group">
                <label>Mot de passe *</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <a href="<?= APP_URL ?>?page=mot-de-passe" class="forgot-link">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="btn-primary btn-full">Se connecter</button>

            <p class="auth-link">Pas encore de compte ? <a href="<?= APP_URL ?>?page=inscription">Créer un compte</a></p>
        </form>
    </div>
</section>