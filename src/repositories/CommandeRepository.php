<?php

require_once __DIR__ . '/../entities/Commande.php';

class CommandeRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findById(int $id): ?Commande {
        $stmt = $this->pdo->prepare("
            SELECT c.*, m.titre AS menu_titre,
                   u.nom AS client_nom, u.prenom AS client_prenom
            FROM commande c
            JOIN menu m ON c.menu_id = m.menu_id
            JOIN utilisateur u ON c.utilisateur_id = u.utilisateur_id
            WHERE c.commande_id = :id
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? new Commande($row) : null;
    }

    public function findByUtilisateur(int $userId): array {
        $stmt = $this->pdo->prepare("
            SELECT c.*, m.titre AS menu_titre
            FROM commande c
            JOIN menu m ON c.menu_id = m.menu_id
            WHERE c.utilisateur_id = :id
            ORDER BY c.date_commande DESC
        ");
        $stmt->execute(['id' => $userId]);
        return array_map(fn($row) => new Commande($row), $stmt->fetchAll());
    }

    public function findAll(array $filtres = []): array {
        $where = ["1=1"];
        $params = [];

        if (!empty($filtres['statut'])) {
            $where[] = "c.statut_actuel = :statut";
            $params['statut'] = $filtres['statut'];
        }
        if (!empty($filtres['client'])) {
            $where[] = "(u.nom LIKE :client OR u.prenom LIKE :client)";
            $params['client'] = '%' . $filtres['client'] . '%';
        }

        $whereSQL = implode(' AND ', $where);
        $stmt = $this->pdo->prepare("
            SELECT c.*, m.titre AS menu_titre,
                   u.nom AS client_nom, u.prenom AS client_prenom
            FROM commande c
            JOIN menu m ON c.menu_id = m.menu_id
            JOIN utilisateur u ON c.utilisateur_id = u.utilisateur_id
            WHERE $whereSQL
            ORDER BY c.date_commande DESC
        ");
        $stmt->execute($params);
        return array_map(fn($row) => new Commande($row), $stmt->fetchAll());
    }

    public function create(array $data): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO commande (
                numero_cmd, utilisateur_id, menu_id, date_prestation,
                heure_livraison, adresse_livraison, ville_livraison,
                nb_personnes, prix_menu, prix_livraison, remise,
                prix_total, pret_materiel, statut_actuel
            ) VALUES (
                :numero_cmd, :utilisateur_id, :menu_id, :date_prestation,
                :heure_livraison, :adresse_livraison, :ville_livraison,
                :nb_personnes, :prix_menu, :prix_livraison, :remise,
                :prix_total, :pret_materiel, 'en_attente'
            )
        ");
        $stmt->execute($data);
        return (int)$this->pdo->lastInsertId();
    }

    public function updateStatut(int $id, string $statut, string $commentaire = ''): void {
        $this->pdo->prepare("
            UPDATE commande SET statut_actuel = :statut WHERE commande_id = :id
        ")->execute(['statut' => $statut, 'id' => $id]);

        $this->pdo->prepare("
            INSERT INTO historique_statut (commande_id, statut, commentaire)
            VALUES (:commande_id, :statut, :commentaire)
        ")->execute([
            'commande_id' => $id,
            'statut'      => $statut,
            'commentaire' => $commentaire,
        ]);
    }

    public function annuler(int $id, string $motif): void {
        $this->pdo->prepare("
            UPDATE commande SET statut_actuel = 'annulee', motif_annulation = :motif
            WHERE commande_id = :id AND statut_actuel = 'en_attente'
        ")->execute(['motif' => $motif, 'id' => $id]);

        $this->pdo->prepare("
            INSERT INTO historique_statut (commande_id, statut, commentaire)
            VALUES (:id, 'annulee', :motif)
        ")->execute(['id' => $id, 'motif' => $motif]);
    }

    public function getHistorique(int $commandeId): array {
        $stmt = $this->pdo->prepare("
            SELECT * FROM historique_statut
            WHERE commande_id = :id
            ORDER BY date_statut ASC
        ");
        $stmt->execute(['id' => $commandeId]);
        return $stmt->fetchAll();
    }
}
