<section class="hero">
    <div class="hero-text">
        <h1>La gastronomie à votre service,<br><span>depuis 25 ans</span></h1>
        <p>Vite & Gourmand vous propose des menus raffinés pour tous vos événements. Noël, Pâques, repas d'affaires — Julie et José s'occupent de tout.</p>
        <div class="hero-cta">
            <a href="<?= APP_URL ?>?page=menus" class="btn-gold">Découvrir nos menus</a>
            <a href="<?= APP_URL ?>?page=contact" class="btn-outline">Nous contacter</a>
        </div>
    </div>
    <div class="hero-img">
        <img src="<?= APP_URL ?>/assets/images/hero.jpg" alt="Table dressée par Vite et Gourmand">
    </div>
</section>

<section class="stats">
    <div class="stat-card">
        <div class="stat-num">25</div>
        <div class="stat-lbl">Ans d'expérience</div>
    </div>
    <div class="stat-card">
        <div class="stat-num">4</div>
        <div class="stat-lbl">Menus disponibles</div>
    </div>
    <div class="stat-card">
        <div class="stat-num">500+</div>
        <div class="stat-lbl">Événements réalisés</div>
    </div>
    <div class="stat-card">
        <div class="stat-num">98%</div>
        <div class="stat-lbl">Clients satisfaits</div>
    </div>
</section>

<section class="equipe">
    <h2>Notre équipe</h2>
    <p class="section-sub">Deux passionnés à votre écoute</p>
    <div class="equipe-grid">
        <div class="card equipe-card">
            <div class="avatar">J</div>
            <h3>Julie Dupont</h3>
            <p>Cheffe cuisinière &amp; logistique</p>
        </div>
        <div class="card equipe-card">
            <div class="avatar">J</div>
            <h3>José Martin</h3>
            <p>Gérant &amp; relations clients</p>
        </div>
    </div>
</section>

<?php if (!empty($avis)): ?>
<section class="avis">
    <h2>Ce que disent nos clients</h2>
    <p class="section-sub">Avis vérifiés par notre équipe</p>
    <div class="avis-grid">
        <?php foreach ($avis as $a): ?>
        <div class="card avis-card">
            <div class="stars">
                <?= str_repeat('★', $a['note']) . str_repeat('☆', 5 - $a['note']) ?>
            </div>
            <p class="avis-text">"<?= htmlspecialchars($a['commentaire']) ?>"</p>
            <div class="avis-author">
                <?= htmlspecialchars($a['prenom']) ?> <?= htmlspecialchars(substr($a['nom'], 0, 1)) ?>. —
                <?= date('F Y', strtotime($a['date_avis'])) ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>