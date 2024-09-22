<?php

namespace core\usecase\categoria;

class ListCategoriasOutput
{
    public function __construct(
        public array $items,
    ) { }
}
