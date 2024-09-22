<?php

namespace core\usecase\atleta;

class DeleteAtletaOutput
{
    public function __construct(
        public bool $sucesso = false,
    ) { }
}
