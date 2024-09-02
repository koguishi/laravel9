<?php

namespace Tests\Feature\usecase\categoria;

use App\Models\Categoria as Model;
use app\repository\eloquent\CategoriaRepository;
use core\domain\exception\NotFoundException;
use core\usecase\categoria\ReadCategoriaInput;
use core\usecase\categoria\ReadCategoriaOutput;
use core\usecase\categoria\ReadCategoriaUsecase;
use Tests\TestCase;
use Throwable;

class ReadCategoriaUsecaseTest extends TestCase
{
    public function testRead()
    {
        $categoriaModel = Model::factory()->create();
        $repository = new CategoriaRepository(new Model());
        $useCase = new ReadCategoriaUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ReadCategoriaInput(
                id: $categoriaModel->id,
            )
        );

        $this->assertInstanceOf(ReadCategoriaOutput::class, $responseUseCase);
        $this->assertEquals($categoriaModel->nome, $responseUseCase->nome);
    }

    public function testReadNotFound()
    {
        try {
            $repository = new CategoriaRepository(new Model());
            $useCase = new ReadCategoriaUsecase($repository);
            $responseUseCase = $useCase->execute(
                new ReadCategoriaInput(
                    id: 'fake',
                )
            );

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }
}
