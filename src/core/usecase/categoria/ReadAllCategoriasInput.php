<?php

namespace core\usecase\categoria;

class ReadAllCategoriasInput
{
    public function __construct(
        public string $filter = '',
        public array $arrOrder = [],
        public int $page = 1,
        public int $totalPage = 15,
    ) { }
}
