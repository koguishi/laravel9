<?php

namespace core\usecase\video;

use DateTime;

class CreateVideoOutput
{
    public function __construct(
        public string $id,
        public string $titulo,
        public string $descricao,
        public ?DateTime $dtFilmagem = null,
        public ?string $pathVideoFile = null,
        public ?string $criadoEm = null
    ) { }
}
