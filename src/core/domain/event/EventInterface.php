<?php

namespace core\domain\event;

interface EventInterface
{
    public function getEventName() : string;
    public function getPayload() : array;
}