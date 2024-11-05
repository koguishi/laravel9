<?php

namespace core\domain\repository;

use core\domain\entity\Categoria;

interface CategoriaRepositoryInterface extends EntityRepositoryInterface
{
    public function getIds(array $categoriasIds = []): array;    

    public function list(string $filter = '', string $order = ''): array;
    public function paginate(
        string $filter = '',
        string $order = '',
        int $page = 1,
        int $totalPage = 15
    ): PaginationInterface;
}
