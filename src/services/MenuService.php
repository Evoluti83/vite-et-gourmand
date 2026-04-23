<?php

require_once __DIR__ . '/../repositories/MenuRepository.php';

class MenuService {
    private MenuRepository $menuRepository;

    public function __construct(MenuRepository $menuRepository) {
        $this->menuRepository = $menuRepository;
    }

    public function getMenusFiltres(array $filtres = []): array {
        return $this->menuRepository->findAll($filtres);
    }

    public function getMenuById(int $id): ?Menu {
        return $this->menuRepository->findById($id);
    }

    public function getMenusAdmin(): array {
        return $this->menuRepository->findAllForAdmin();
    }

    public function toggleActif(int $menuId, bool $actif): void {
        $this->menuRepository->toggleActif($menuId, $actif);
    }

    public function getMenusForJson(array $filtres = []): array {
        $menus = $this->menuRepository->findAll($filtres);
        return array_map(fn(Menu $m) => $m->toArray(), $menus);
    }
}