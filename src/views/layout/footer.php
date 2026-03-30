</main>

<footer class="footer">
    <div class="footer-col">
        <div class="footer-logo">Vite &amp; Gourmand</div>
        <p>12 rue des Saveurs<br>33000 Bordeaux<br>contact@viteetgourmand.fr<br>06 12 34 56 78</p>
    </div>

    <div class="footer-col">
        <div class="footer-titre">Horaires</div>
        <?php
        require_once __DIR__ . '/../../config/db.php';
        $pdo = getDB();
        $horaires = $pdo->query("SELECT * FROM horaire ORDER BY horaire_id")->fetchAll();
        foreach ($horaires as $h):
        ?>
            <p>
                <?= ucfirst($h['jour']) ?> :
                <?= $h['heure_ouverture'] ? $h['heure_ouverture'] . ' – ' . $h['heure_fermeture'] : 'Fermé' ?>
            </p>
        <?php endforeach; ?>
    </div>

    <div class="footer-col">
        <div class="footer-titre">Informations</div>
        <ul>
            <li><a href="<?= APP_URL ?>?page=mentions">Mentions légales</a></li>
            <li><a href="<?= APP_URL ?>?page=cgv">Conditions générales de vente</a></li>
        </ul>
    </div>
</footer>

<script src="<?= APP_URL ?>/assets/js/main.js"></script>
</body>
</html>