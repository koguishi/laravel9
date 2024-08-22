<?php

namespace core\usecase\categoria;

class ReadAllCategoriasInput
{
    public function __construct(
        public string $filter = '',
        public string $order = 'ASC',
    ) { }
}
