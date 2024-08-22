<?php

namespace core\usecase\categoria;

class UpdateCategoriaOutput
{
    public function __construct(
        public string $id,
        public string $nome,
        public ?string $descricao = null,
        public ?bool $ativo = null,
        public string $criadoEm = ''
    ) { }
}
