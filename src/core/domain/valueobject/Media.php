<?php

namespace core\domain\valueobject;

use core\domain\enum\MediaStatus;

class Media
{
    public function __construct(
        protected string $filePath,
        protected MediaStatus $mediaStatus,
        protected string $encodedPath = '',
    ) {
    }

    public function __get($property)
    {
        return $this->{$property};
    }
}