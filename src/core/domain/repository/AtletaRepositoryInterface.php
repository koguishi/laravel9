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
        string $order = '',
        string $filter_nome = '',
        ?DateTime $filter_dtNascimento_inicial = null,
        ?DateTime $filter_dtNascimento_final = null,
    ): array;

    public function paginate(
        int $page = 1,
        int $perPage = 15,
        string $order = '',
        string $filter_nome = '',
        ?DateTime $filter_dtNascimento_inicial = null,
        ?DateTime $filter_dtNascimento_final = null,
    ): PaginationInterface;
}
