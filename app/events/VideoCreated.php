<?php

namespace app\events;

use core\usecase\video\VideoEventManagerInterface;

class VideoCreated implements VideoEventManagerInterface
{
    public function dispatch(object $event): void
    {
    }
}