<?php

namespace core\usecase\atleta;

use DateTime;

class ReadAtletaInput
{
    public function __construct(
        public string $id,
    ) { }
}
