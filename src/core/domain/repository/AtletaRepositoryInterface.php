<?php

namespace core\domain\repository;

use core\domain\entity\Atleta;

interface AtletaRepositoryInterface
{
    public function create(Atleta $atleta): Atleta;
    public function read(string $id): Atleta;
    public function update(Atleta $atleta): Atleta;
    public function delete(string $id): bool;

    public function readAll(string $filter = '', string $order = ''): array;
    public function paginate(
        string $filter = '',
        string $order = '',
        int $page = 1,
        int $totalPage = 15
    ): PaginationInterface;
}
