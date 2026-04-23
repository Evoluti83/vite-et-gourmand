<?php

require_once __DIR__ . '/../entities/Menu.php';

class MenuRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findAll(array $filtres = []): array {
        $where = ["m.actif = 1"];
        $params = [];

        if (!empty($filtres['prix_max'])) {
            $where[] = "m.prix_base <= :prix_max";
            $params['prix_max'] = (float)$filtres['prix_max'];
        }
        if (!empty($filtres['prix_min'])) {
            $where[] = "m.prix_base >= :prix_min";
            $params['prix_min'] = (float)$filtres['prix_min'];
        }
        if (!empty($filtres['theme_id'])) {
            $where[] = "m.theme_id = :theme_id";
            $params['theme_id'] = (int)$filtres['theme_id'];
        }
        if (!empty($filtres['regime_id'])) {
            $where[] = "m.regime_id = :regime_id";
            $params['regime_id'] = (int)$filtres['regime_id'];
        }
        if (!empty($filtres['nb_pers'])) {
            $where[] = "m.nb_pers_min <= :nb_pers";
            $params['nb_pers'] = (int)$filtres['nb_pers'];
        }

        $whereSQL = implode(' AND ', $where);
        $stmt = $this->pdo->prepare("
            SELECT m.*, t.libelle AS theme, r.libelle AS regime,
                   i.chemin AS image
            FROM menu m
            LEFT JOIN theme t ON m.theme_id = t.theme_id
            LEFT JOIN regime r ON m.regime_id = r.regime_id
            LEFT JOIN image_menu i ON i.image_id = (
                SELECT image_id FROM image_menu
                WHERE menu_id = m.menu_id
                ORDER BY ordre LIMIT 1
            )
            WHERE $whereSQL
            ORDER BY m.menu_id ASC
        ");
        $stmt->execute($params);

        return array_map(fn($row) => new Menu($row), $stmt->fetchAll());
    }

    public function findById(int $id): ?Menu {
        $stmt = $this->pdo->prepare("
            SELECT m.*, t.libelle AS theme, r.libelle AS regime
            FROM menu m
            LEFT JOIN theme t ON m.theme_id = t.theme_id
            LEFT JOIN regime r ON m.regime_id = r.regime_id
            WHERE m.menu_id = :id AND m.actif = 1
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? new Menu($row) : null;
    }

    public function findAllForAdmin(): array {
        $stmt = $this->pdo->query("
            SELECT m.*, t.libelle AS theme, r.libelle AS regime
            FROM menu m
            LEFT JOIN theme t ON m.theme_id = t.theme_id
            LEFT JOIN regime r ON m.regime_id = r.regime_id
            ORDER BY m.menu_id ASC
        ");
        return array_map(fn($row) => new Menu($row), $stmt->fetchAll());
    }

    public function decrementStock(int $menuId): void {
        $this->pdo->prepare("
            UPDATE menu SET stock = stock - 1 WHERE menu_id = :id AND stock > 0
        ")->execute(['id' => $menuId]);
    }

    public function toggleActif(int $menuId, bool $actif): void {
        $this->pdo->prepare("
            UPDATE menu SET actif = :actif WHERE menu_id = :id
        ")->execute(['actif' => $actif ? 1 : 0, 'id' => $menuId]);
    }
}