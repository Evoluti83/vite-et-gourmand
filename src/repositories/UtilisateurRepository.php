<?php

require_once __DIR__ . '/../entities/Utilisateur.php';

class UtilisateurRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email): ?Utilisateur {
        $stmt = $this->pdo->prepare("
            SELECT u.*, r.libelle AS role
            FROM utilisateur u
            JOIN role r ON u.role_id = r.role_id
            WHERE u.email = :email AND u.actif = 1
        ");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return $row ? new Utilisateur($row) : null;
    }

    public function findById(int $id): ?Utilisateur {
        $stmt = $this->pdo->prepare("
            SELECT u.*, r.libelle AS role
            FROM utilisateur u
            JOIN role r ON u.role_id = r.role_id
            WHERE u.utilisateur_id = :id
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? new Utilisateur($row) : null;
    }

    public function findAllEmployes(): array {
        $stmt = $this->pdo->query("
            SELECT u.*, r.libelle AS role
            FROM utilisateur u
            JOIN role r ON u.role_id = r.role_id
            WHERE u.role_id = 2
            ORDER BY u.nom ASC
        ");
        return array_map(fn($row) => new Utilisateur($row), $stmt->fetchAll());
    }

    public function create(array $data): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO utilisateur (email, password, nom, prenom, gsm, adresse, ville, role_id)
            VALUES (:email, :password, :nom, :prenom, :gsm, :adresse, :ville, :role_id)
        ");
        $stmt->execute([
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'nom'      => $data['nom'],
            'prenom'   => $data['prenom'],
            'gsm'      => $data['gsm'] ?? '',
            'adresse'  => $data['adresse'] ?? '',
            'ville'    => $data['ville'] ?? '',
            'role_id'  => $data['role_id'] ?? 3,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void {
        $this->pdo->prepare("
            UPDATE utilisateur
            SET nom = :nom, prenom = :prenom, gsm = :gsm,
                adresse = :adresse, ville = :ville
            WHERE utilisateur_id = :id
        ")->execute([
            'nom'     => $data['nom'],
            'prenom'  => $data['prenom'],
            'gsm'     => $data['gsm'],
            'adresse' => $data['adresse'],
            'ville'   => $data['ville'],
            'id'      => $id,
        ]);
    }

    public function toggleActif(int $id, bool $actif): void {
        $this->pdo->prepare("
            UPDATE utilisateur SET actif = :actif
            WHERE utilisateur_id = :id AND role_id = 2
        ")->execute(['actif' => $actif ? 1 : 0, 'id' => $id]);
    }

    public function emailExists(string $email): bool {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM utilisateur WHERE email = :email
        ");
        $stmt->execute(['email' => $email]);
        return (bool)$stmt->fetchColumn();
    }

    public function updatePassword(int $id, string $hashedPassword): void {
        $this->pdo->prepare("
            UPDATE utilisateur SET password = :password WHERE utilisateur_id = :id
        ")->execute(['password' => $hashedPassword, 'id' => $id]);
    }

    public function findByResetToken(string $token): ?Utilisateur {
        $stmt = $this->pdo->prepare("
            SELECT u.*, r.libelle AS role
            FROM utilisateur u
            JOIN role r ON u.role_id = r.role_id
            WHERE u.reset_token = :token AND u.reset_expiration > NOW()
        ");
        $stmt->execute(['token' => $token]);
        $row = $stmt->fetch();
        return $row ? new Utilisateur($row) : null;
    }
}