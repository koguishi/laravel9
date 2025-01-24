<?php

namespace Tests\Stubs;

use core\usecase\video\VideoEventManagerInterface;


class VideoEventStub implements VideoEventManagerInterface
{
    public function dispatch(object $event): void {}

}