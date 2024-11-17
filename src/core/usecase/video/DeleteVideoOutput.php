<?php

namespace core\usecase\video;

class DeleteVideoOutput
{
    public function __construct(
        public bool $sucesso = false,
    ) { }
}
