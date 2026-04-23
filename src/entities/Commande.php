<?php

class Commande {
    public int $commande_id;
    public string $numero_cmd;
    public int $utilisateur_id;
    public int $menu_id;
    public string $date_commande;
    public string $date_prestation;
    public string $heure_livraison;
    public string $adresse_livraison;
    public string $ville_livraison;
    public int $nb_personnes;
    public float $prix_menu;
    public float $prix_livraison;
    public bool $remise;
    public float $prix_total;
    public bool $pret_materiel;
    public string $statut_actuel;
    public ?string $motif_annulation;
    public ?string $mode_contact;
    public ?string $menu_titre;
    public ?string $client_nom;
    public ?string $client_prenom;

    public const STATUTS = [
        'en_attente'              => 'En attente',
        'accepte'                 => 'Acceptée',
        'en_preparation'          => 'En préparation',
        'en_cours_de_livraison'   => 'En cours de livraison',
        'livre'                   => 'Livrée',
        'en_attente_retour_materiel' => 'Retour matériel',
        'terminee'                => 'Terminée',
        'annulee'                 => 'Annulée',
    ];

    public function __construct(array $data) {
        $this->commande_id       = (int)($data['commande_id'] ?? 0);
        $this->numero_cmd        = $data['numero_cmd'] ?? '';
        $this->utilisateur_id    = (int)($data['utilisateur_id'] ?? 0);
        $this->menu_id           = (int)($data['menu_id'] ?? 0);
        $this->date_commande     = $data['date_commande'] ?? '';
        $this->date_prestation   = $data['date_prestation'] ?? '';
        $this->heure_livraison   = $data['heure_livraison'] ?? '';
        $this->adresse_livraison = $data['adresse_livraison'] ?? '';
        $this->ville_livraison   = $data['ville_livraison'] ?? '';
        $this->nb_personnes      = (int)($data['nb_personnes'] ?? 0);
        $this->prix_menu         = (float)($data['prix_menu'] ?? 0);
        $this->prix_livraison    = (float)($data['prix_livraison'] ?? 0);
        $this->remise            = (bool)($data['remise'] ?? false);
        $this->prix_total        = (float)($data['prix_total'] ?? 0);
        $this->pret_materiel     = (bool)($data['pret_materiel'] ?? false);
        $this->statut_actuel     = $data['statut_actuel'] ?? 'en_attente';
        $this->motif_annulation  = $data['motif_annulation'] ?? null;
        $this->mode_contact      = $data['mode_contact'] ?? null;
        $this->menu_titre        = $data['menu_titre'] ?? null;
        $this->client_nom        = $data['client_nom'] ?? null;
        $this->client_prenom     = $data['client_prenom'] ?? null;
    }

    public function getStatutLibelle(): string {
        return self::STATUTS[$this->statut_actuel] ?? $this->statut_actuel;
    }

    public function getPrixTotalFormate(): string {
        return number_format($this->prix_total, 2, ',', ' ') . ' €';
    }

    public function isAnnulable(): bool {
        return $this->statut_actuel === 'en_attente';
    }

    public function isTerminee(): bool {
        return $this->statut_actuel === 'terminee';
    }

    public function toArray(): array {
        return [
            'commande_id'       => $this->commande_id,
            'numero_cmd'        => $this->numero_cmd,
            'utilisateur_id'    => $this->utilisateur_id,
            'menu_id'           => $this->menu_id,
            'date_commande'     => $this->date_commande,
            'date_prestation'   => $this->date_prestation,
            'heure_livraison'   => $this->heure_livraison,
            'adresse_livraison' => $this->adresse_livraison,
            'ville_livraison'   => $this->ville_livraison,
            'nb_personnes'      => $this->nb_personnes,
            'prix_menu'         => $this->prix_menu,
            'prix_livraison'    => $this->prix_livraison,
            'remise'            => $this->remise,
            'prix_total'        => $this->prix_total,
            'pret_materiel'     => $this->pret_materiel,
            'statut_actuel'     => $this->statut_actuel,
            'motif_annulation'  => $this->motif_annulation,
            'mode_contact'      => $this->mode_contact,
            'menu_titre'        => $this->menu_titre,
            'client_nom'        => $this->client_nom,
            'client_prenom'     => $this->client_prenom,
        ];
    }
}