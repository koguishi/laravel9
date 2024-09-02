<?php

namespace Tests\Feature\usecase\categoria;

use App\Models\Categoria as Model;
use app\repository\eloquent\CategoriaRepository;
use core\domain\exception\NotFoundException;
use core\usecase\categoria\UpdateCategoriaInput;
use core\usecase\categoria\UpdateCategoriaOutput;
use core\usecase\categoria\UpdateCategoriaUsecase;
use Tests\TestCase;
use Throwable;

class UpdateCategoriaUsecaseTest extends TestCase
{
    public function testUpdate()
    {
        $categoriaModel = Model::factory()->create();

        $this->assertDatabaseHas('categorias', [
            'id' => $categoriaModel->id,
            'nome' => $categoriaModel->nome,
        ]);

        $repository = new CategoriaRepository(new Model());
        $useCase = new UpdateCategoriaUsecase($repository);
        $responseUseCase = $useCase->execute(
            new UpdateCategoriaInput(
                id: $categoriaModel->id,
                nome: 'nome alterado',
            )
        );
        $this->assertInstanceOf(UpdateCategoriaOutput::class, $responseUseCase);

        $this->assertDatabaseMissing('categorias', [
            'nome' => $categoriaModel->nome,
        ]);
        $this->assertDatabaseHas('categorias', [
            'id' => $categoriaModel->id,
            'nome' => $responseUseCase->nome,
        ]);

    }

    public function testReadNotFound()
    {
        try {
            $repository = new CategoriaRepository(new Model());
            $useCase = new UpdateCategoriaUsecase($repository);
            $responseUseCase = $useCase->execute(
                new UpdateCategoriaInput(
                    id: 'fake',
                )
            );

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }

}
