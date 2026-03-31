<section class="auth-section">
    <div class="auth-card">
        <h1>Nous contacter</h1>
        <p class="section-sub">Une question ? Nous vous répondrons dans les plus brefs délais</p>

        <?php if ($success): ?>
            <div class="alert alert-success">
                Votre message a bien été envoyé ! Nous vous répondrons à l'adresse <?= htmlspecialchars($_POST['email']) ?>.
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
                <label>Titre *</label>
                <input type="text" name="titre" value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>" required placeholder="Ex: Question sur le menu Noël">
            </div>

            <div class="form-group">
                <label>Description *</label>
                <textarea name="description" rows="5" required placeholder="Décrivez votre demande..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>Votre adresse email *</label>
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required placeholder="votre@email.fr">
            </div>

            <button type="submit" class="btn-primary btn-full">Envoyer le message</button>
        </form>
        <?php endif; ?>

        <div class="contact-info">
            <div class="contact-item">
                <strong>Téléphone</strong>
                <span>06 12 34 56 78</span>
            </div>
            <div class="contact-item">
                <strong>Email</strong>
                <span>contact@viteetgourmand.fr</span>
            </div>
            <div class="contact-item">
                <strong>Adresse</strong>
                <span>12 rue des Saveurs, 33000 Bordeaux</span>
            </div>
        </div>
    </div>
</section>