<?php

namespace core\usecase\atleta;

use DateTime;

class ListAtletasInput
{
    public function __construct(
        public string $filter_nome = '',
        public string $order = '',
        public ?DateTime $filter_dtNascimento_inicial = null,
        public ?DateTime $filter_dtNascimento_final = null,
    ) { }
}
