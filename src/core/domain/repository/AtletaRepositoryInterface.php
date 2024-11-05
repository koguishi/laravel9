<?php

namespace core\domain\repository;

use core\domain\entity\Atleta;
use DateTime;

interface AtletaRepositoryInterface extends EntityRepositoryInterface
{
    public function getIds(array $atletasIds = []): array;

    public function list(
        string $order = '',
        string $filter_nome = '',
        ?DateTime $filter_dtNascimento_inicial = null,
        ?DateTime $filter_dtNascimento_final = null,
    ): array;

    public function paginate(
        int $page = 1,
        int $perPage = 15,
        ?string $order = '',
        ?string $filter_nome = '',
        ?DateTime $filter_dtNascimento_inicial = null,
        ?DateTime $filter_dtNascimento_final = null,
    ): PaginationInterface;
}
