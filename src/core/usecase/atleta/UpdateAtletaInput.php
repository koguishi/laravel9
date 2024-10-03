<?php

namespace core\usecase\atleta;

use DateTime;

class UpdateAtletaInput
{
    public function __construct(
        public string $id,
        public ?string $nome = null,
        public ?string $dtNascimento = null,
    ) { }
}
