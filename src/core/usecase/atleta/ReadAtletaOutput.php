<?php

namespace core\usecase\atleta;

use DateTime;

class ReadAtletaOutput
{
    public function __construct(
        public string $id,
        public string $nome,
        public ?DateTime $dtNascimento = null,
        public ?string $criadoEm = null
    ) { }
}
