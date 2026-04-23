<?php

require_once __DIR__ . '/../repositories/UtilisateurRepository.php';

class UtilisateurService {
    private UtilisateurRepository $utilisateurRepository;

    public function __construct(UtilisateurRepository $utilisateurRepository) {
        $this->utilisateurRepository = $utilisateurRepository;
    }

    public function connecter(string $email, string $password): ?Utilisateur {
        $utilisateur = $this->utilisateurRepository->findByEmail($email);
        if (!$utilisateur) {
            return null;
        }
        if (!password_verify($password, $utilisateur->email)) {
            return null;
        }
        return $utilisateur;
    }

    public function inscrire(array $data): int {
        if ($this->utilisateurRepository->emailExists($data['email'])) {
            throw new Exception('Cette adresse email est déjà utilisée.');
        }
        return $this->utilisateurRepository->create($data);
    }

    public function getById(int $id): ?Utilisateur {
        return $this->utilisateurRepository->findById($id);
    }

    public function mettreAJourProfil(int $id, array $data): void {
        $this->utilisateurRepository->update($id, $data);
    }

    public function getEmployes(): array {
        return $this->utilisateurRepository->findAllEmployes();
    }

    public function toggleActif(int $id, bool $actif): void {
        $this->utilisateurRepository->toggleActif($id, $actif);
    }

    public function creerEmploye(array $data): int {
        if ($this->utilisateurRepository->emailExists($data['email'])) {
            throw new Exception('Cette adresse email est déjà utilisée.');
        }
        $data['role_id'] = 2;
        return $this->utilisateurRepository->create($data);
    }

    public function reinitialiserMotDePasse(int $id, string $nouveauMotDePasse): void {
        $hash = password_hash($nouveauMotDePasse, PASSWORD_BCRYPT);
        $this->utilisateurRepository->updatePassword($id, $hash);
    }

    public function getByResetToken(string $token): ?Utilisateur {
        return $this->utilisateurRepository->findByResetToken($token);
    }

    public function validerMotDePasse(string $password): bool {
        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/';
        return (bool)preg_match($regex, $password);
    }
}