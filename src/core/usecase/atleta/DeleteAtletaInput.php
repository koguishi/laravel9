<?php

namespace core\usecase\atleta;

class DeleteAtletaInput
{
    public function __construct(
        public string $id,
    ) { }
}
