<?php

namespace core\usecase\atleta;

use DateTime;

class PaginateAtletasInput
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 15,
        public string $order = '',
        public string $filter_nome = '',
        public ?DateTime $filter_dtNascimento_inicial = null,
        public ?DateTime $filter_dtNascimento_final = null,
    ) { }
}
