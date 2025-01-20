<?php

namespace app\services;

use core\usecase\interfaces\FileStorageInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileStorage implements FileStorageInterface
{
    public function store(string $path, array $file): string
    {
        $laravelFile = $this->convertToLaravelFile((array)$file);
        
        return Storage::putFile($path, $laravelFile);
    }
    
    public function delete(string $path)
    {
        Storage::delete($path);
    }

    protected function convertToLaravelFile(array $file): UploadedFile
    {
        return new UploadedFile(
            path: $file['tmp_name'],
            originalName: $file['name'],
            mimeType: $file['type'],
            error: $file['error'],
        );
    }
}