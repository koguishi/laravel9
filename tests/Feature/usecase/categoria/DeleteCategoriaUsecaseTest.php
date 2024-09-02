<?php

namespace Tests\Feature\usecase\categoria;

use App\Models\Categoria as Model;
use app\repository\eloquent\CategoriaRepository;
use core\domain\exception\NotFoundException;
use core\usecase\categoria\CreateCategoriaUsecase;
use core\usecase\categoria\DeleteCategoriaInput;
use core\usecase\categoria\DeleteCategoriaOutput;
use core\usecase\categoria\DeleteCategoriaUsecase;
use Database\Factories\CategoriaFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Throwable;

class DeleteCategoriaUsecaseTest extends TestCase
{
    public function testDelete()
    {
        $categoriaModel = Model::factory()->create();

        $this->assertDatabaseHas('categorias', [
            'id' => $categoriaModel->id,
        ]);

        $repository = new CategoriaRepository(new Model());
        $useCase = new DeleteCategoriaUsecase($repository);
        $responseUseCase = $useCase->execute(
            new DeleteCategoriaInput(
                id: $categoriaModel->id,
            )
        );
        $this->assertInstanceOf(DeleteCategoriaOutput::class, $responseUseCase);
        $this->assertTrue($responseUseCase->sucesso);

        $this->assertSoftDeleted($categoriaModel);
        // $this->assertDatabaseMissing('categorias', [
        //     'id' => $categoriaModel->id,
        // ]);
    }

    public function testDeleteNotFound()
    {
        try {
            $repository = new CategoriaRepository(new Model());
            $useCase = new DeleteCategoriaUsecase($repository);
            $responseUseCase = $useCase->execute(
                new DeleteCategoriaInput(
                    id: 'fake',
                )
            );

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }
}
