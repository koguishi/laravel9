<?php

namespace Tests\Feature\usecase\categoria;

use App\Models\Categoria as Model;
use app\repository\eloquent\CategoriaRepository;
use core\usecase\categoria\ReadAllCategoriasInput;
use core\usecase\categoria\ReadAllCategoriasOutput;
use core\usecase\categoria\ReadAllCategoriasUsecase;
use Tests\TestCase;

class ReadAllCategoriasUsecaseTest extends TestCase
{
    public function testReadAll()
    {
        $categoriasModel = Model::factory()->count(20)->create();
        $repository = new CategoriaRepository(new Model());
        $useCase = new ReadAllCategoriasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ReadAllCategoriasInput()
        );

        $this->assertInstanceOf(ReadAllCategoriasOutput::class, $responseUseCase);
        $this->assertEquals(count($categoriasModel), count($responseUseCase->items));
    }

    public function testReadAllEmpty()
    {
        $repository = new CategoriaRepository(new Model());
        $useCase = new ReadAllCategoriasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ReadAllCategoriasInput()
        );

        $this->assertInstanceOf(ReadAllCategoriasOutput::class, $responseUseCase);
        $this->assertCount(0, $responseUseCase->items);
    }

    public function testReadAllFiltered()
    {
        $categoriasModel = Model::factory()->count(20)->create();
        $repository = new CategoriaRepository(new Model());
        $useCase = new ReadAllCategoriasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ReadAllCategoriasInput(
                filter: $categoriasModel[0]->nome
            )
        );

        $this->assertInstanceOf(ReadAllCategoriasOutput::class, $responseUseCase);
        $this->assertGreaterThanOrEqual(1, count($responseUseCase->items));
        foreach ($responseUseCase->items as $key => $item) {
            $this->assertTrue(str_contains($item['nome'], $categoriasModel[0]->nome));
        }
    }

    public function testReadAllFilteredNotFound()
    {
        $categoriasModel = Model::factory()->count(20)->create();
        $repository = new CategoriaRepository(new Model());
        $useCase = new ReadAllCategoriasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ReadAllCategoriasInput(
                filter: 'filtro qualquer 1234'
            )
        );

        $this->assertInstanceOf(ReadAllCategoriasOutput::class, $responseUseCase);
        $this->assertCount(0, $responseUseCase->items);
    }

    public function testReadAllOrderByNomeAsc()
    {
        $categoriasModel = Model::factory()->count(20)->create();
        $arrNomes = [];
        foreach ($categoriasModel as $key => $categoriaModel) {
            array_push($arrNomes, $categoriaModel->nome);
        }
        sort($arrNomes);

        $repository = new CategoriaRepository(new Model());
        $useCase = new ReadAllCategoriasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ReadAllCategoriasInput(
                order: '{"nome": "ASC"}',
            )
        );

        $this->assertInstanceOf(ReadAllCategoriasOutput::class, $responseUseCase);
        $this->assertEquals(count($categoriasModel), count($responseUseCase->items));

        foreach ($responseUseCase->items as $key => $item) {
            $this->assertEquals($arrNomes[$key], $item['nome']);
        }
    }
}
