-- ========================================
-- Vite & Gourmand - Base de données SQL
-- ========================================
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Table ROLE
CREATE TABLE role (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

-- Table UTILISATEUR
CREATE TABLE utilisateur (
    utilisateur_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    gsm VARCHAR(20),
    adresse VARCHAR(255),
    ville VARCHAR(100),
    actif BOOLEAN DEFAULT TRUE,
    role_id INT NOT NULL,
    FOREIGN KEY (role_id) REFERENCES role(role_id)
);

-- Table THEME
CREATE TABLE theme (
    theme_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

-- Table REGIME
CREATE TABLE regime (
    regime_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

-- Table MENU
CREATE TABLE menu (
    menu_id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(100) NOT NULL,
    description TEXT,
    nb_pers_min INT NOT NULL,
    prix_base DOUBLE NOT NULL,
    conditions TEXT,
    stock INT DEFAULT 0,
    actif BOOLEAN DEFAULT TRUE,
    theme_id INT,
    regime_id INT,
    FOREIGN KEY (theme_id) REFERENCES theme(theme_id),
    FOREIGN KEY (regime_id) REFERENCES regime(regime_id)
);

-- Table IMAGE_MENU
CREATE TABLE image_menu (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    menu_id INT NOT NULL,
    chemin VARCHAR(255) NOT NULL,
    ordre INT DEFAULT 0,
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id)
);

-- Table PLAT
CREATE TABLE plat (
    plat_id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    type ENUM('entree', 'plat', 'dessert') NOT NULL,
    photo BLOB
);

-- Table MENU_PLAT (liaison menu <-> plat)
CREATE TABLE menu_plat (
    menu_id INT NOT NULL,
    plat_id INT NOT NULL,
    PRIMARY KEY (menu_id, plat_id),
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id),
    FOREIGN KEY (plat_id) REFERENCES plat(plat_id)
);

-- Table ALLERGENE
CREATE TABLE allergene (
    allergene_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL
);

-- Table PLAT_ALLERGENE (liaison plat <-> allergène)
CREATE TABLE plat_allergene (
    plat_id INT NOT NULL,
    allergene_id INT NOT NULL,
    PRIMARY KEY (plat_id, allergene_id),
    FOREIGN KEY (plat_id) REFERENCES plat(plat_id),
    FOREIGN KEY (allergene_id) REFERENCES allergene(allergene_id)
);

-- Table COMMANDE
CREATE TABLE commande (
    commande_id INT AUTO_INCREMENT PRIMARY KEY,
    numero_cmd VARCHAR(50) NOT NULL UNIQUE,
    utilisateur_id INT NOT NULL,
    menu_id INT NOT NULL,
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_prestation DATE NOT NULL,
    heure_livraison TIME NOT NULL,
    adresse_livraison VARCHAR(255) NOT NULL,
    ville_livraison VARCHAR(100) NOT NULL,
    nb_personnes INT NOT NULL,
    prix_menu DOUBLE NOT NULL,
    prix_livraison DOUBLE DEFAULT 0,
    remise BOOLEAN DEFAULT FALSE,
    prix_total DOUBLE NOT NULL,
    pret_materiel BOOLEAN DEFAULT FALSE,
    statut_actuel VARCHAR(50) DEFAULT 'en_attente',
    motif_annulation TEXT,
    mode_contact VARCHAR(50),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id),
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id)
);

-- Table HISTORIQUE_STATUT
CREATE TABLE historique_statut (
    historique_id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    statut VARCHAR(50) NOT NULL,
    date_statut DATETIME DEFAULT CURRENT_TIMESTAMP,
    commentaire TEXT,
    FOREIGN KEY (commande_id) REFERENCES commande(commande_id)
);

-- Table AVIS
CREATE TABLE avis (
    avis_id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    utilisateur_id INT NOT NULL,
    note INT CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    statut ENUM('en_attente', 'valide', 'refuse') DEFAULT 'en_attente',
    date_avis DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (commande_id) REFERENCES commande(commande_id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id)
);

-- Table HORAIRE
CREATE TABLE horaire (
    horaire_id INT AUTO_INCREMENT PRIMARY KEY,
    jour ENUM('lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche') NOT NULL,
    heure_ouverture VARCHAR(5),
    heure_fermeture VARCHAR(5)
);

SET FOREIGN_KEY_CHECKS = 1;