<?php

namespace core\usecase\categoria;

class DeleteCategoriaOutput
{
    public function __construct(
        public bool $sucesso = false,
    ) { }
}
