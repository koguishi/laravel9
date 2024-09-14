<?php

namespace core\usecase\categoria;

class PaginateCategoriasInput
{
    public function __construct(
        public string $filter = '',
        public string $order = '',
        public int $page = 1,
        public int $totalPage = 15,
    ) { }
}
