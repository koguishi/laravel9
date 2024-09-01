<?php

namespace Tests\Feature\usecase\categoria;

use App\Models\Categoria as Model;
use app\repository\eloquent\CategoriaRepository;
use core\usecase\categoria\CreateCategoriaInput;
use core\usecase\categoria\CreateCategoriaUsecase;
use Tests\TestCase;

class CreateCategoriaUsecaseTest extends TestCase
{
    public function testCreate()
    {
        $repository = new CategoriaRepository(new Model());
        $useCase = new CreateCategoriaUsecase($repository);
        $responseUseCase = $useCase->execute(
            new CreateCategoriaInput(
                nome: 'Teste',
            )
        );

        $this->assertEquals('Teste', $responseUseCase->nome);
        $this->assertNotEmpty($responseUseCase->id);

        $this->assertDatabaseHas('categorias', [
            'id' => $responseUseCase->id,
        ]);
    }
}
