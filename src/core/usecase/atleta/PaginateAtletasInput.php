<?php

namespace core\usecase\atleta;

class PaginateAtletasInput
{
    public function __construct(
        public string $filter = '',
        public string $order = '',
        public int $page = 1,
        public int $totalPage = 15,
    ) { }
}
