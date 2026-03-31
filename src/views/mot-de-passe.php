<section class="auth-section">
    <div class="auth-card">

        <?php if ($_GET['etape'] ?? 'demande' === 'demande'): ?>

            <h1>Mot de passe oublié</h1>
            <p class="section-sub">Entrez votre email pour recevoir un lien de réinitialisation</p>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    Si cette adresse existe, un email vous a été envoyé avec un lien de réinitialisation. Vérifiez votre boîte mail.
                </div>
            <?php endif; ?>

            <?php if (!empty($erreurs)): ?>
                <div class="alert alert-error">
                    <?php foreach ($erreurs as $e): ?>
                        <p><?= htmlspecialchars($e) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!$success): ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Adresse email *</label>
                    <input type="email" name="email" required autofocus>
                </div>
                <button type="submit" class="btn-primary btn-full">Envoyer le lien</button>
                <p class="auth-link"><a href="<?= APP_URL ?>?page=connexion">Retour à la connexion</a></p>
            </form>
            <?php endif; ?>

        <?php else: ?>

            <h1>Nouveau mot de passe</h1>
            <p class="section-sub">Choisissez un nouveau mot de passe sécurisé</p>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    Votre mot de passe a été modifié avec succès !
                    <a href="<?= APP_URL ?>?page=connexion">Se connecter</a>
                </div>
            <?php endif; ?>

            <?php if (!empty($erreurs)): ?>
                <div class="alert alert-error">
                    <?php foreach ($erreurs as $e): ?>
                        <p><?= htmlspecialchars($e) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!$success): ?>
            <form method="POST" action="">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <div class="form-group">
                    <label>Nouveau mot de passe *</label>
                    <input type="password" name="password" required>
                    <small>10 caractères minimum, une majuscule, une minuscule, un chiffre, un caractère spécial</small>
                </div>
                <div class="form-group">
                    <label>Confirmer le mot de passe *</label>
                    <input type="password" name="confirm" required>
                </div>
                <button type="submit" class="btn-primary btn-full">Réinitialiser</button>
            </form>
            <?php endif; ?>

        <?php endif; ?>

    </div>
</section>