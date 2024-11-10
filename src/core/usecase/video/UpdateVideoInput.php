<?php

namespace core\usecase\video;

use DateTime;

class UpdateVideoInput
{
    public function __construct( 
        public string $id,
        public string $titulo,
        public string $descricao,
        public DateTime $dtFilmagem,
        public array $categoriasIds,
        public array $atletasIds,
        public ?array $videoFile = null,
    ) { }
}
