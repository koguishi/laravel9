<?php

namespace core\usecase\video;

use DateTime;

class ReadVideoInput
{
    public function __construct(
        public string $id,
    ) { }
}
