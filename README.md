# Vite & Gourmand — Application de traiteur

Application web PHP natif développée dans le cadre de l'ECF TP DWWM (Studi).

## Présentation

Vite & Gourmand est une application de commande en ligne pour une entreprise de traiteur bordelaise. Elle permet aux clients de consulter et commander des menus, aux employés de gérer les commandes et aux administrateurs de piloter l'activité.

## Stack technique

- **Back-end** : PHP 8.2 (PDO, pattern MVC, Front Controller)
- **Base de données relationnelle** : MySQL/MariaDB
- **Base de données NoSQL** : MongoDB Atlas
- **Mails** : PHPMailer + Gmail SMTP
- **Déploiement** : Heroku + JawsDB
- **Versioning** : Git/GitHub

## URL de production

https://vite-gourmand-2026-3769160ca332.herokuapp.com

## Comptes de démonstration

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | jose@viteetgourmand.fr | Password1! |
| Employé | julie@viteetgourmand.fr | Password1! |
| Utilisateur | client@test.fr | Password1! |

## Déploiement en local

### Prérequis

- XAMPP (PHP 8.2+, Apache, MySQL)
- Composer
- Git
- Extension PHP MongoDB (`php_mongodb.dll`)

### Installation

**1. Cloner le repository**
```bash
git clone https://github.com/Evoluti83/vite-et-gourmand.git
cd vite-et-gourmand
```

**2. Installer les dépendances Composer**
```bash
php composer.phar install
```

**3. Activer l'extension MongoDB**

- Télécharger `php_mongodb.dll` depuis pecl.php.net (version PHP 8.2 TS x64)
- Copier dans `C:\xampp\php\ext\`
- Ajouter `extension=mongodb` dans `C:\xampp\php\php.ini`
- Redémarrer Apache

**4. Créer la base de données**

- Ouvrir phpMyAdmin : `http://localhost/phpmyadmin`
- Créer une base de données `vite_et_gourmand`
- Importer `database/create.sql`
- Importer `database/insert.sql`

**5. Configurer la connexion base de données**

Le fichier `src/config/db.php` détecte automatiquement l'environnement :
- En local : utilise `localhost`, user `root`, password vide, base `vite_et_gourmand`
- En production : utilise la variable d'environnement `JAWSDB_URL`

**6. Configurer MongoDB**

Dans `src/config/mongodb.php`, vérifier la chaîne de connexion Atlas :
```php
$client = new MongoDB\Client(
    'mongodb+srv://vite_gourmand:PASSWORD@cluster0.7dp6pji.mongodb.net/'
);
```

**7. Configurer PHPMailer**

Dans `src/config/mail.php`, renseigner les identifiants Gmail :
```php
$mail->Username = 'votre.email@gmail.com';
$mail->Password = 'votre_app_password';
```

> En production, utiliser des variables d'environnement : `getenv('SMTP_USER')` et `getenv('SMTP_PASS')`

**8. Mettre à jour les mots de passe de démonstration**

Créer et exécuter un fichier temporaire `public/hash.php` :
```php
<?php
echo password_hash('Password1!', PASSWORD_BCRYPT);
```

Puis mettre à jour dans phpMyAdmin :
```sql
UPDATE utilisateur 
SET password = 'HASH_GENERE' 
WHERE email IN ('client@test.fr', 'julie@viteetgourmand.fr', 'jose@viteetgourmand.fr');
```

**9. Lancer l'application**

- Démarrer Apache et MySQL dans XAMPP
- Accéder à : `http://localhost/vite-et-gourmand/public`

## Structure du projet
```
vite-et-gourmand/
├── database/
│   ├── create.sql          # Schéma de la base de données
│   ├── insert.sql          # Données de démonstration
│   └── mcd.png             # Modèle Conceptuel de Données
├── public/
│   ├── index.php           # Front Controller
│   ├── .htaccess           # Réécriture d'URL
│   └── assets/
│       ├── css/style.css   # Styles
│       ├── js/main.js      # JavaScript
│       └── images/         # Images
├── src/
│   ├── config/
│   │   ├── config.php      # Constantes application
│   │   ├── db.php          # Connexion PDO (Singleton)
│   │   ├── mongodb.php     # Connexion MongoDB (Singleton)
│   │   └── mail.php        # PHPMailer + fonctions mail
│   ├── controllers/        # Controllers MVC
│   └── views/              # Vues PHP
├── vendor/                 # Dépendances Composer (ignoré par Git)
├── composer.json
├── Procfile                # Configuration Heroku
└── README.md
```

## Déploiement sur Heroku

**1. Créer l'application**
```bash
heroku create nom-de-app
heroku addons:create jawsdb:kitefin
```

**2. Configurer les variables d'environnement**
```bash
heroku config:set APP_URL=https://nom-de-app.herokuapp.com
```

**3. Pousser le code**
```bash
git push heroku main
```

**4. Importer la base de données**

Créer temporairement `public/import.php` pour exécuter `create.sql` et `insert.sql` via PHP, puis le supprimer après import.

## Fonctionnalités principales

| US | Fonctionnalité |
|----|----------------|
| US01 | Page d'accueil avec avis validés |
| US02 | Vue globale menus + filtres AJAX |
| US03 | Détail menu avec composition et allergènes |
| US04 | Inscription + mail de bienvenue |
| US05 | Connexion + déconnexion |
| US06 | Réinitialisation mot de passe par mail |
| US07 | Formulaire de contact |
| US08/09 | Commande avec calcul de prix dynamique |
| US11-16 | Espace utilisateur complet |
| US17-23 | Espace employé complet |
| US24-29 | Espace administrateur complet |

## Notes importantes pour la soutenance

- **Frais de livraison km** : En production, intégration de l'API Google Maps Distance Matrix pour calculer la distance réelle
- **Mots de passe en dur** : En production, variables d'environnement `getenv()`
- **Stats admin** : Données depuis MongoDB Atlas (collection `commandes_stats`)
- **Formulaires POST** : URL directe `index.php` contourne une limitation du `.htaccess` XAMPP en local