-- ========================================
-- Vite & Gourmand - Données de test
-- ========================================

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Rôles
INSERT INTO role (libelle) VALUES
('administrateur'),
('employe'),
('utilisateur');

-- Utilisateurs
-- Mot de passe : Password1! (hashé en production)
INSERT INTO utilisateur (email, password, nom, prenom, gsm, adresse, ville, actif, role_id) VALUES
('jose@viteetgourmand.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Martin', 'José', '0612345678', '12 rue des Saveurs', 'Bordeaux', TRUE, 1),
('julie@viteetgourmand.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dupont', 'Julie', '0698765432', '12 rue des Saveurs', 'Bordeaux', TRUE, 2),
('client@test.fr', '$2y$10$vvnbOfIf7qtnoVvplLzGW.DDfDdDJtaTDusm9EwLzSeCHxLalpeHy', 'Durand', 'Thomas', '0611223344', '5 avenue de la Paix', 'Bordeaux', TRUE, 3);

-- Thèmes
INSERT INTO theme (libelle) VALUES
('Noël'),
('Pâques'),
('Classique'),
('Événement');

-- Régimes
INSERT INTO regime (libelle) VALUES
('Classique'),
('Végétarien'),
('Vegan');

-- Menus
INSERT INTO menu (titre, description, nb_pers_min, prix_base, conditions, stock, actif, theme_id, regime_id) VALUES
('Menu Noël Prestige', 'Un menu raffiné pour sublimer vos fêtes de fin d année', 8, 320.00, 'À commander 7 jours avant la prestation', 5, TRUE, 1, 1),
('Menu Pâques Gourmand', 'Célébrez Pâques avec nos spécialités de saison', 6, 180.00, 'À commander 5 jours avant la prestation', 8, TRUE, 2, 1),
('Menu Classique Affaires', 'Idéal pour vos repas professionnels et événements d entreprise', 10, 250.00, 'À commander 3 jours avant la prestation', 10, TRUE, 3, 1),
('Menu Végétarien Printemps', 'Une cuisine végétarienne créative et savoureuse', 4, 120.00, 'À commander 3 jours avant la prestation', 6, TRUE, 4, 2);

-- Images menus
INSERT INTO image_menu (image_id, menu_id, chemin, ordre) VALUES
(6, 1, 'assets/images/menus/menu_1_1775206297_0.jpg', 3),
(7, 1, 'assets/images/menus/menu_1_1775206384_0.jpg', 4),
(8, 2, 'assets/images/menus/menu_2_1775206459_0.jpg', 2),
(9, 2, 'assets/images/menus/menu_2_1775206469_0.jpg', 3),
(10, 3, 'assets/images/menus/menu_3_1775206483_0.jpg', 1),
(11, 3, 'assets/images/menus/menu_3_1775206490_0.jpg', 2),
(12, 4, 'assets/images/menus/menu_4_1775206559_0.jpg', 1),
(13, 4, 'assets/images/menus/menu_4_1775206565_0.jpg', 2);

-- Plats
INSERT INTO plat (nom, type) VALUES
('Foie gras maison', 'entree'),
('Velouté de butternut', 'entree'),
('Salade de chèvre chaud', 'entree'),
('Magret de canard aux cèpes', 'plat'),
('Filet de boeuf en croûte', 'plat'),
('Risotto aux truffes', 'plat'),
('Tarte Tatin revisitée', 'dessert'),
('Bûche de Noël artisanale', 'dessert'),
('Mousse au chocolat', 'dessert');

-- Allergènes
INSERT INTO allergene (libelle) VALUES
('Gluten'),
('Oeufs'),
('Lait'),
('Fruits à coque'),
('Céleri'),
('Moutarde'),
('Sulfites');

-- Liaison plat <-> allergène
INSERT INTO plat_allergene (plat_id, allergene_id) VALUES
(1, 2), (1, 7),
(2, 3),
(3, 2), (3, 3),
(4, 7),
(5, 1), (5, 2),
(6, 3),
(7, 1), (7, 2), (7, 3),
(8, 1), (8, 2), (8, 3), (8, 4),
(9, 2), (9, 3);

-- Liaison menu <-> plat
INSERT INTO menu_plat (menu_id, plat_id) VALUES
(1, 1), (1, 5), (1, 8),
(2, 2), (2, 4), (2, 7),
(3, 3), (3, 4), (3, 9),
(4, 2), (4, 6), (4, 7);

-- Horaires
INSERT INTO horaire (jour, heure_ouverture, heure_fermeture) VALUES
('lundi', '09:00', '18:00'),
('mardi', '09:00', '18:00'),
('mercredi', '09:00', '18:00'),
('jeudi', '09:00', '18:00'),
('vendredi', '09:00', '19:00'),
('samedi', '10:00', '17:00'),
('dimanche', NULL, NULL);

-- Commande de test
INSERT INTO commande (numero_cmd, utilisateur_id, menu_id, date_prestation, heure_livraison, adresse_livraison, ville_livraison, nb_personnes, prix_menu, prix_livraison, remise, prix_total, pret_materiel, statut_actuel) VALUES
('CMD-2025-001', 3, 1, '2025-12-24', '19:00:00', '5 avenue de la Paix', 'Bordeaux', 8, 320.00, 0.00, FALSE, 320.00, FALSE, 'accepte');

-- Historique statut
INSERT INTO historique_statut (commande_id, statut, commentaire) VALUES
(1, 'en_attente', 'Commande reçue'),
(1, 'accepte', 'Commande validée par Julie');

-- Avis de test
INSERT INTO avis (commande_id, utilisateur_id, note, commentaire, statut) VALUES
(1, 3, 5, 'Prestation exceptionnelle, nous recommandons vivement !', 'valide');

SET FOREIGN_KEY_CHECKS = 1;