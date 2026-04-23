<?php

class Utilisateur {
    public int $utilisateur_id;
    public string $email;
    public string $nom;
    public string $prenom;
    public string $gsm;
    public string $adresse;
    public string $ville;
    public bool $actif;
    public int $role_id;
    public ?string $role;

    public function __construct(array $data) {
        $this->utilisateur_id = (int)($data['utilisateur_id'] ?? 0);
        $this->email          = $data['email'] ?? '';
        $this->nom            = $data['nom'] ?? '';
        $this->prenom         = $data['prenom'] ?? '';
        $this->gsm            = $data['gsm'] ?? '';
        $this->adresse        = $data['adresse'] ?? '';
        $this->ville          = $data['ville'] ?? '';
        $this->actif          = (bool)($data['actif'] ?? true);
        $this->role_id        = (int)($data['role_id'] ?? 3);
        $this->role           = $data['role'] ?? null;
    }

    public function getNomComplet(): string {
        return $this->prenom . ' ' . $this->nom;
    }

    public function isAdmin(): bool {
        return $this->role === 'administrateur';
    }

    public function isEmploye(): bool {
        return in_array($this->role, ['employe', 'administrateur']);
    }

    public function toArray(): array {
        return [
            'utilisateur_id' => $this->utilisateur_id,
            'email'          => $this->email,
            'nom'            => $this->nom,
            'prenom'         => $this->prenom,
            'gsm'            => $this->gsm,
            'adresse'        => $this->adresse,
            'ville'          => $this->ville,
            'actif'          => $this->actif,
            'role_id'        => $this->role_id,
            'role'           => $this->role,
        ];
    }
}