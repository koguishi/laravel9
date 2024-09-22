<?php

namespace Tests\Feature\usecase\categoria;

use App\Models\Categoria as Model;
use app\repository\eloquent\CategoriaRepository;
use core\usecase\categoria\ListCategoriasInput;
use core\usecase\categoria\ListCategoriasOutput;
use core\usecase\categoria\ListCategoriasUsecase;
use Tests\TestCase;

class ListCategoriasUsecaseTest extends TestCase
{
    public function testReadAll()
    {
        $categoriasModel = Model::factory()->count(20)->create();
        $repository = new CategoriaRepository(new Model());
        $useCase = new ListCategoriasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ListCategoriasInput()
        );

        $this->assertInstanceOf(ListCategoriasOutput::class, $responseUseCase);
        $this->assertEquals(count($categoriasModel), count($responseUseCase->items));
    }

    public function testReadAllEmpty()
    {
        $repository = new CategoriaRepository(new Model());
        $useCase = new ListCategoriasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ListCategoriasInput()
        );

        $this->assertInstanceOf(ListCategoriasOutput::class, $responseUseCase);
        $this->assertCount(0, $responseUseCase->items);
    }

    public function testReadAllFiltered()
    {
        $categoriasModel = Model::factory()->count(20)->create();
        $repository = new CategoriaRepository(new Model());
        $useCase = new ListCategoriasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ListCategoriasInput(
                filter: $categoriasModel[0]->nome
            )
        );

        $this->assertInstanceOf(ListCategoriasOutput::class, $responseUseCase);
        $this->assertGreaterThanOrEqual(1, count($responseUseCase->items));
        foreach ($responseUseCase->items as $key => $item) {
            $this->assertTrue(str_contains($item['nome'], $categoriasModel[0]->nome));
        }
    }

    public function testReadAllFilteredNotFound()
    {
        $categoriasModel = Model::factory()->count(20)->create();
        $repository = new CategoriaRepository(new Model());
        $useCase = new ListCategoriasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ListCategoriasInput(
                filter: 'filtro qualquer 1234'
            )
        );

        $this->assertInstanceOf(ListCategoriasOutput::class, $responseUseCase);
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
        $useCase = new ListCategoriasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ListCategoriasInput(
                order: '{"nome": "ASC"}',
            )
        );

        $this->assertInstanceOf(ListCategoriasOutput::class, $responseUseCase);
        $this->assertEquals(count($categoriasModel), count($responseUseCase->items));

        foreach ($responseUseCase->items as $key => $item) {
            $this->assertEquals($arrNomes[$key], $item['nome']);
        }
    }
}
