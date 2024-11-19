<?php

namespace app\services;

use core\usecase\interfaces\FileStorageInterface;

class FileStorage implements FileStorageInterface
{
    public function store(string $path, array $file): string
    {
        return '';
    }
    
    public function delete(string $path)
    {

    }
}