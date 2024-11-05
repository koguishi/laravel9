<?php

namespace core\usecase\video;

use DateTime;

class CreateVideoInput
{
    public function __construct( 
        public string $titulo,
        public string $descricao,
        public DateTime $dtFilmagem,
        public array $categoriasIds,
        public array $atletasIds,
        public ?array $videoMedia = null,
    ) { }
}
