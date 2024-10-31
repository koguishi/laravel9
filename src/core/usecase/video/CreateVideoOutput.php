<?php

namespace core\usecase\video;

use DateTime;

class CreateVideoOutput
{
    public function __construct(
        public string $titulo,
        public string $descricao,
        public ?DateTime $dtFilmagem = null,
        public ?string $criadoEm = null
    ) { }
}
