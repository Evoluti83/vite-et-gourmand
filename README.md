# Vite & Gourmand — Application de traiteur

Application web PHP natif développée dans le cadre de l'ECF TP DWWM (Studi).

## Présentation

Vite & Gourmand est une application de commande en ligne pour une entreprise de traiteur bordelaise. Elle permet aux clients de consulter et commander des menus, aux employés de gérer les commandes et aux administrateurs de piloter l'activité.

## Stack technique

- **Back-end** : PHP 8.3 (PDO, pattern MVC, Front Controller, architecture en couches)
- **Architecture** : Entities / Repositories / Services / Controllers
- **Base de données relationnelle** : MySQL/MariaDB
- **Base de données NoSQL** : MongoDB Atlas
- **Mails** : PHPMailer + Gmail SMTP
- **Déploiement** : Heroku + JawsDB
- **Conteneurisation** : Docker + Docker Compose
- **Versioning** : Git/GitHub

## Liens du projet

- **Application** : https://vite-gourmand-2026-3769160ca332.herokuapp.com
- **GitHub** : https://github.com/Evoluti83/vite-et-gourmand
- **Gestion de projet (Trello)** : https://trello.com/b/bwMKgCcx/vite-gourmand

## Comptes de démonstration

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | jose@viteetgourmand.fr | Password1! |
| Employé | julie@viteetgourmand.fr | Password1! |
| Utilisateur | client@test.fr | Password1! |

## Déploiement avec Docker (recommandé)

### Prérequis

- Docker Desktop installé et lancé
- Git

### Installation

**1. Cloner le repository**
```bash
git clone https://github.com/Evoluti83/vite-et-gourmand.git
cd vite-et-gourmand
```

**2. Lancer les containers**
```bash
docker compose up --build
```

Docker va automatiquement :
- Créer le container PHP 8.3 + Apache
- Créer le container MySQL 8.0
- Importer `database/create.sql` et `database/insert.sql`
- Installer les dépendances Composer

**3. Accéder à l'application**
http://localhost:8080

**4. Mettre à jour les mots de passe**

```bash
docker exec -it vite-gourmand-db mysql -u vite_user -pvite_pass vite_et_gourmand -e "
UPDATE utilisateur SET password = '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE email IN ('client@test.fr', 'julie@viteetgourmand.fr', 'jose@viteetgourmand.fr');
"
```

**5. Arrêter les containers**

```bash
docker compose down
```

---

## Déploiement en local sans Docker (Laragon)

### Prérequis

- Laragon (PHP 8.3+, Apache, MySQL)
- Composer
- Git
- Extension PHP MongoDB

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

- Télécharger `php_mongodb.dll` depuis pecl.php.net (version PHP 8.3 TS x64)
- Copier dans `C:\laragon\bin\php\php-8.3.x\ext\`
- Ajouter `extension=mongodb` dans `php.ini`
- Redémarrer Laragon

**4. Créer la base de données**

- Ouvrir phpMyAdmin via Laragon
- Créer une base de données `vite_et_gourmand`
- Importer `database/create.sql`
- Importer `database/insert.sql`

**5. Lancer l'application**

- Démarrer Apache et MySQL dans Laragon
- Accéder à : `http://localhost/vite-et-gourmand/public`

---

## Structure du projet
vite-et-gourmand/
├── database/
│   ├── create.sql              # Schéma de la base de données (16 tables)
│   ├── insert.sql              # Données de démonstration
│   └── mcd.png                 # Modèle Conceptuel de Données
├── docker/
│   └── apache.conf             # Configuration Apache pour Docker
├── public/
│   ├── index.php               # Front Controller
│   ├── .htaccess               # Réécriture d'URL
│   ├── api/
│   │   └── menus.php           # Endpoint JSON pour la Fetch API
│   └── assets/
│       ├── css/style.css       # Styles CSS personnalisés
│       ├── js/main.js          # JavaScript
│       └── images/             # Images
├── src/
│   ├── config/
│   │   ├── autoload.php        # Chargement automatique des classes
│   │   ├── config.php          # Constantes application
│   │   ├── db.php              # Connexion PDO (Singleton)
│   │   ├── mongodb.php         # Connexion MongoDB (Singleton)
│   │   └── mail.php            # PHPMailer + fonctions mail
│   ├── entities/               # Objets métier (Menu, Commande, Utilisateur)
│   ├── repositories/           # Accès aux données PDO
│   ├── services/               # Logique métier
│   ├── controllers/            # Controllers MVC
│   └── views/                  # Vues PHP
├── vendor/                     # Dépendances Composer (ignoré par Git)
├── composer.json
├── Dockerfile                  # Image Docker PHP + Apache
├── docker-compose.yml          # Orchestration containers
├── Procfile                    # Configuration Heroku
└── README.md

## Architecture en couches
Requête HTTP
↓
Controller        ← orchestration, session, guard RBAC
↓
Service           ← logique métier (calcul prix, validation)
↓
Repository        ← accès données PDO
↓
Entity            ← objets métier typés
↓
Base de données   ← MySQL / MongoDB

## Déploiement sur Heroku

**1. Créer l'application**
```bash
heroku create nom-de-app
heroku addons:create jawsdb:kitefin
heroku config:set APP_URL=https://nom-de-app.herokuapp.com
```

**2. Pousser le code**
```bash
git push heroku main
```

**3. Importer la base de données**

Créer temporairement `public/import.php` pour exécuter `create.sql` et `insert.sql` via PHP, puis le supprimer après import.

## Fonctionnalités principales

| US | Fonctionnalité |
|----|----------------|
| US01 | Page d'accueil avec avis validés |
| US02 | Vue globale menus + filtres Fetch API (sans rechargement) |
| US03 | Détail menu avec composition et allergènes |
| US04 | Inscription + mail de bienvenue |
| US05 | Connexion + déconnexion |
| US06 | Réinitialisation mot de passe par mail |
| US07 | Formulaire de contact |
| US08/09 | Commande avec calcul de prix dynamique |
| US11-16 | Espace utilisateur complet |
| US17-23 | Espace employé complet |
| US24-29 | Espace administrateur complet |

## Notes importantes

- **Frais de livraison km** : En production, intégration de l'API Google Maps Distance Matrix
- **Mots de passe SMTP** : En production, variables d'environnement `getenv('SMTP_PASS')`
- **Stats admin** : Données depuis MongoDB Atlas (collection `commandes_stats`)
- **Formulaires POST** : URL directe `index.php` contourne une limitation du `.htaccess` Laragon en local