<?php

namespace core\usecase\categoria;

class DeleteCategoriaInput
{
    public function __construct(
        public string $id,
    ) { }
}
