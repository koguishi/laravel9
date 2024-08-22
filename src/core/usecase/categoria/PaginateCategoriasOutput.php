<?php

namespace core\usecase\categoria;

class PaginateCategoriasOutput
{
    public function __construct(
        public array $items,
        public int $total,
        public int $current_page,
        public int $last_page,
        public int $first_page,
        public int $per_page,
        public int $to,
        public int $from,
    ) { }
}
