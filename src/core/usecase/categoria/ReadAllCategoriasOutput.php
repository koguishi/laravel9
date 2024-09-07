<?php

namespace core\usecase\categoria;

class ReadAllCategoriasOutput
{
    public function __construct(
        public array $items,
    ) { }
}
