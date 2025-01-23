<?php

namespace Tests\Feature\usecase\video;

use App\Models\Atleta as AtletaModel;
use App\Models\Categoria as CategoriaModel;
use core\domain\repository\AtletaRepositoryInterface;
use core\domain\repository\CategoriaRepositoryInterface;
use core\domain\repository\VideoRepositoryInterface;
use core\usecase\interfaces\FileStorageInterface;
use core\usecase\interfaces\TransactionInterface;
use core\usecase\video\CreateVideoInput;
use core\usecase\video\CreateVideoUsecase;
use core\usecase\video\VideoEventManagerInterface;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CreateVideoUsecaseTest extends TestCase
{
    public function test_create()
    {
        $videoRepository = $this->app->make(VideoRepositoryInterface::class);
        $categoriaRepository = $this->app->make(CategoriaRepositoryInterface::class);
        $atletaRepository = $this->app->make(AtletaRepositoryInterface::class);
        $transaction = $this->app->make(TransactionInterface::class);
        $fileStorage = $this->app->make(FileStorageInterface::class);
        $eventManager = $this->app->make(VideoEventManagerInterface::class);

        $usecase = new CreateVideoUsecase(
            $videoRepository,
            $transaction,
            $fileStorage,
            $eventManager,
            $categoriaRepository,
            $atletaRepository,
        );

        $idsCategorias = CategoriaModel::factory(3)->create()->pluck('id')->toArray();
        $idsAtletas = AtletaModel::factory(3)->create()->pluck('id')->toArray();

        $fakeFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');
        $file = [
            'tmp_name' => $fakeFile->getPathname(),
            'name' => $fakeFile->getFileName(),
            'type' => $fakeFile->getMimeType(),
            'error' => $fakeFile->getError(),
            //'size' => $fakeFile->getSize(),
        ];

        $input = new CreateVideoInput(
            titulo: 'titulo',
            descricao: 'descrição',
            dtFilmagem: new DateTime('2001-01-01'),
            categoriasIds: $idsCategorias,
            atletasIds: $idsAtletas,
            videoFile: $file,
        );

        $response = $usecase->execute($input);

        $this->assertEquals('titulo', $response->titulo);
        $this->assertEquals('descrição', $response->descricao);
        // $this->assertEquals('dtFilmagem', $response->dtFilmagem);
        $this->assertCount(3, $response->categorias);
        $this->assertCount(3, $response->atletas);
        $this->assertNotNull($response->pathVideoFile);
    }
}
