<?php

namespace core\usecase\atleta;

use DateTime;

class CreateAtletaInput
{
    public function __construct(
        public string $nome,
        public DateTime $dtNascimento,
    ) { }
}
