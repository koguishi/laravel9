<?php

namespace core\usecase\video;

use DateTime;

class UpdateVideoOutput
{
    public function __construct(
        public string $id,
        public string $titulo,
        public string $descricao,
        public ?DateTime $dtFilmagem = null,
        public ?string $pathVideoFile = null,
        public ?string $criadoEm = null,
        public ?array $categorias = null,
        public ?array $atletas = null,
    ) { }
}
