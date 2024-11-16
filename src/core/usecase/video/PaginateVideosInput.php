<?php

namespace core\usecase\video;

use DateTime;

class PaginateVideosInput
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 15,
        public ?string $order = '',
        public ?string $filter = '',
        public ?DateTime $filter_dtNascimento_inicial = null,
        public ?DateTime $filter_dtNascimento_final = null,
    ) { }
}
