<?php

namespace core\usecase\categoria;

class ListCategoriasInput
{
    public function __construct(
        public string $filter = '',
        public string $order = '',
    ) { }
}
