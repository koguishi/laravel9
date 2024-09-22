<?php

namespace core\usecase\atleta;

use DateTime;

class ListAtletasInput
{
    public function __construct(
        public string $filter = '',
        public string $order = '',
    ) { }
}
