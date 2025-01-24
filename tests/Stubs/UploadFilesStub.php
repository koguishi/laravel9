<?php

namespace Tests\Stubs;

use core\usecase\interfaces\FileStorageInterface;

class UploadFilesStub implements FileStorageInterface
{
    public function store(string $path, array $file): string
    {
        return "{$path}/video-qualquer.mp4";
    }
    public function delete(string $path) {}
}