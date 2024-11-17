<?php

namespace core\usecase\video;

class DeleteVideoInput
{
    public function __construct(
        public string $id,
    ) { }
}
