<?php

namespace core\usecase\atleta;

use DateTime;

class UpdateAtletaInput
{
    public function __construct(
        public string $id,
        public string $nome,
        public DateTime $dtNascimento,
    ) { }
}
