<?php

namespace core\usecase\atleta;

use DateTime;

class CreateAtletaOutput
{
    public function __construct(
        public string $id,
        public string $nome,
        public ?DateTime $dtNascimento = null,
        public ?string $criadoEm = null
    ) { }
}
