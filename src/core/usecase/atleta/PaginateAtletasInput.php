<?php

namespace core\usecase\atleta;

class PaginateAtletasInput
{
    public function __construct(
        public string $order = '',
        public string $filter = '',
        public int $page = 1,
        public int $perPage = 15,
    ) { }
}
