<?php

namespace core\usecase\interfaces;

interface FileStorageInterface
{
    /**
     * Summary of store
     * @param string $path
     * @param $_FILES $file
     * @return string
     */
    public function store(string $path, array $file): string;
    public function delete(string $path);
}