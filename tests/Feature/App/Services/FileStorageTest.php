<?php

namespace Tests\Feature\App\Services;

use app\services\FileStorage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileStorageTest extends TestCase
{
    public function test_store()
    {
        $fakeFile = UploadedFile::fake()->create(
            name: 'o_nome_real_do_arquivo.mp4',
            kilobytes: 1,
            mimeType: 'video/mp4',
        );

        $file = [
            'tmp_name' => $fakeFile->getPathname(),
            'name' => $fakeFile->getClientOriginalName(),
            'type' => $fakeFile->getClientMimeType(),
            'error' => $fakeFile->getError(),
        ];

        $filePath = (new FileStorage())->store('videos', $file);

        Storage::assertExists($filePath);

        Storage::delete($filePath);
    }

    public function test_delete()
    {
        $file = UploadedFile::fake()->create(
            name: 'o_nome_real_do_arquivo.mp4',
            kilobytes: 1,
            mimeType: 'video/mp4',
        );

        $filePath = $file->store('videos');

        Storage::assertExists($filePath);

        (new FileStorage())->delete($filePath);

        Storage::assertMissing($filePath);
    }
}
