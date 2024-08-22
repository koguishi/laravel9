<?php

namespace core\usecase\categoria;

class ReadCategoriaInput
{
    public function __construct(
        public string $id,
    ) { }
}
