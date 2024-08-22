<?php

namespace core\usecase\categoria;

class UpdateCategoriaInput
{
    public function __construct(
        public string $id,
        public ?string $nome = null,
        public ?string $descricao = null,
        public ?bool $ativo = null
    ) { }
}
