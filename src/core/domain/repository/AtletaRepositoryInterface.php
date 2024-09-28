<?php

namespace core\domain\repository;

use core\domain\entity\Atleta;
use DateTime;

interface AtletaRepositoryInterface
{
    public function create(Atleta $atleta): Atleta;
    public function read(string $id): Atleta;
    public function update(Atleta $atleta): Atleta;
    public function delete(string $id): bool;

    public function list(
        string $filter_nome = '',
        string $order = '',
        ?DateTime $filter_dtNascimento_inicial,
        ?DateTime $filter_dtNascimento_final,
    ): array;

    public function paginate(
        string $filter = '',
        string $order = '',
        int $page = 1,
        int $totalPage = 15
    ): PaginationInterface;
}
