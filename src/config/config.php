<?php

define('APP_NAME', 'Vite & Gourmand');
define('APP_URL', 'http://localhost/vite-et-gourmand/public');
define('APP_VERSION', '1.0.0');

define('BORDEAUX_LAT', 44.8378);
define('BORDEAUX_LNG', -0.5792);
define('LIVRAISON_BASE', 5.00);
define('LIVRAISON_KM', 0.59);
define('REMISE_PERSONNES', 5);
define('REMISE_TAUX', 0.10);

define('SESSION_DURATION', 3600);

date_default_timezone_set('Europe/Paris');

session_start();