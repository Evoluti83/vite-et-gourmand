<?php

require_once __DIR__ . '/../repositories/CommandeRepository.php';
require_once __DIR__ . '/../repositories/MenuRepository.php';
require_once __DIR__ . '/../entities/Commande.php';

class CommandeService {
    private CommandeRepository $commandeRepository;
    private MenuRepository $menuRepository;

    public function __construct(
        CommandeRepository $commandeRepository,
        MenuRepository $menuRepository
    ) {
        $this->commandeRepository = $commandeRepository;
        $this->menuRepository     = $menuRepository;
    }

    public function calculerPrix(Menu $menu, int $nbPersonnes, string $ville): array {
        $prixMenu = $menu->prix_base;
        $remise   = false;

        if ($nbPersonnes >= $menu->nb_pers_min + REMISE_PERSONNES) {
            $prixMenu *= (1 - REMISE_TAUX);
            $remise    = true;
        }

        $prixLivraison = 0;
        if (strtolower(trim($ville)) !== 'bordeaux') {
            $prixLivraison = LIVRAISON_BASE;
        }

        $prixTotal = $prixMenu + $prixLivraison;

        return [
            'prix_menu'      => round($prixMenu, 2),
            'prix_livraison' => round($prixLivraison, 2),
            'remise'         => $remise,
            'prix_total'     => round($prixTotal, 2),
        ];
    }

    public function creerCommande(array $data, int $utilisateurId): int {
        $menu = $this->menuRepository->findById((int)$data['menu_id']);
        if (!$menu || !$menu->isDisponible()) {
            throw new Exception('Menu indisponible.');
        }

        $nbPersonnes = (int)$data['nb_personnes'];
        if ($nbPersonnes < $menu->nb_pers_min) {
            throw new Exception('Nombre de personnes insuffisant.');
        }

        $prix = $this->calculerPrix($menu, $nbPersonnes, $data['ville_livraison']);

        $numeroCmd = 'CMD-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(4)));

        $commandeId = $this->commandeRepository->create([
            'numero_cmd'        => $numeroCmd,
            'utilisateur_id'    => $utilisateurId,
            'menu_id'           => $menu->menu_id,
            'date_prestation'   => $data['date_prestation'],
            'heure_livraison'   => $data['heure_livraison'],
            'adresse_livraison' => $data['adresse_livraison'],
            'ville_livraison'   => $data['ville_livraison'],
            'nb_personnes'      => $nbPersonnes,
            'prix_menu'         => $prix['prix_menu'],
            'prix_livraison'    => $prix['prix_livraison'],
            'remise'            => $prix['remise'] ? 1 : 0,
            'prix_total'        => $prix['prix_total'],
            'pret_materiel'     => isset($data['pret_materiel']) ? 1 : 0,
        ]);

        $this->commandeRepository->updateStatut($commandeId, 'en_attente', 'Commande créée');
        $this->menuRepository->decrementStock($menu->menu_id);

        return $commandeId;
    }

    public function getCommandesByUser(int $userId): array {
        return $this->commandeRepository->findByUtilisateur($userId);
    }

    public function getCommandeById(int $id): ?Commande {
        return $this->commandeRepository->findById($id);
    }

    public function getCommandesAdmin(array $filtres = []): array {
        return $this->commandeRepository->findAll($filtres);
    }

    public function updateStatut(int $commandeId, string $statut, string $commentaire = ''): void {
        $statuts_valides = array_keys(Commande::STATUTS);
        if (!in_array($statut, $statuts_valides)) {
            throw new Exception('Statut invalide.');
        }
        $this->commandeRepository->updateStatut($commandeId, $statut, $commentaire);
    }

    public function annuler(int $commandeId, int $userId, string $motif = ''): void {
        $commande = $this->commandeRepository->findById($commandeId);
        if (!$commande || $commande->utilisateur_id !== $userId) {
            throw new Exception('Commande introuvable.');
        }
        if (!$commande->isAnnulable()) {
            throw new Exception('Cette commande ne peut plus être annulée.');
        }
        $this->commandeRepository->annuler($commandeId, $motif);
    }

    public function getHistorique(int $commandeId): array {
        return $this->commandeRepository->getHistorique($commandeId);
    }
}