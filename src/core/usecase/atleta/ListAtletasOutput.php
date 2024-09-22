<?php

namespace core\usecase\atleta;

use DateTime;

class ListAtletasOutput
{
    public function __construct(
        public array $items,
    ) { }
}
