<?php

namespace core\usecase\interfaces;

interface EventManagerInterface
{
    public function dispatch(object $event): void;
}