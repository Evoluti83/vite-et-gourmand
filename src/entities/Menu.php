<?php

class Menu {
    public int $menu_id;
    public string $titre;
    public string $description;
    public int $nb_pers_min;
    public float $prix_base;
    public string $conditions;
    public int $stock;
    public bool $actif;
    public ?int $theme_id;
    public ?int $regime_id;
    public ?string $theme;
    public ?string $regime;
    public ?string $image;

    public function __construct(array $data) {
        $this->menu_id     = (int)($data['menu_id'] ?? 0);
        $this->titre       = $data['titre'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->nb_pers_min = (int)($data['nb_pers_min'] ?? 0);
        $this->prix_base   = (float)($data['prix_base'] ?? 0);
        $this->conditions  = $data['conditions'] ?? '';
        $this->stock       = (int)($data['stock'] ?? 0);
        $this->actif       = (bool)($data['actif'] ?? true);
        $this->theme_id    = isset($data['theme_id']) ? (int)$data['theme_id'] : null;
        $this->regime_id   = isset($data['regime_id']) ? (int)$data['regime_id'] : null;
        $this->theme       = $data['theme'] ?? null;
        $this->regime      = $data['regime'] ?? null;
        $this->image       = $data['image'] ?? null;
    }

    public function getPrixFormate(): string {
        return number_format($this->prix_base, 2, ',', ' ') . ' €';
    }

    public function isDisponible(): bool {
        return $this->actif && $this->stock > 0;
    }

    public function toArray(): array {
        return [
            'menu_id'     => $this->menu_id,
            'titre'       => $this->titre,
            'description' => $this->description,
            'nb_pers_min' => $this->nb_pers_min,
            'prix_base'   => $this->prix_base,
            'conditions'  => $this->conditions,
            'stock'       => $this->stock,
            'actif'       => $this->actif,
            'theme_id'    => $this->theme_id,
            'regime_id'   => $this->regime_id,
            'theme'       => $this->theme,
            'regime'      => $this->regime,
            'image'       => $this->image,
        ];
    }
}