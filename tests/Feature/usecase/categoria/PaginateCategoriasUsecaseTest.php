<?php

namespace Tests\Feature\usecase\categoria;

use App\Models\Categoria as MOdel;
use app\repository\eloquent\CategoriaRepository;
use core\usecase\categoria\PaginateCategoriasInput;
use core\usecase\categoria\PaginateCategoriasOutput;
use core\usecase\categoria\PaginateCategoriasUsecase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaginateCategoriasUsecaseTest extends TestCase
{
    public function testPaginate()
    {
        $categoriasModel = Model::factory()->count(20)->create();
        $repository = new CategoriaRepository(new Model());
        $useCase = new PaginateCategoriasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new PaginateCategoriasInput()
        );
        $this->assertInstanceOf(PaginateCategoriasOutput::class, $responseUseCase);
        $this->assertCount($responseUseCase->per_page, $responseUseCase->items);
    }

    public function testPaginateTotal20PerPage7Current2()
    {
        $categoriasModel = Model::factory()->count(20)->create();
        $repository = new CategoriaRepository(new Model());
        $useCase = new PaginateCategoriasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new PaginateCategoriasInput(
                page: 2,
                totalPage: 7
            )
        );
        $this->assertInstanceOf(PaginateCategoriasOutput::class, $responseUseCase);
        $this->assertEquals(2, $responseUseCase->current_page);
        $this->assertEquals(8, $responseUseCase->to);
        $this->assertEquals(14, $responseUseCase->from);
        $this->assertCount(7, $responseUseCase->items);
    }

    public function testPaginateTotal20PerPage7Current3()
    {
        $categoriasModel = Model::factory()->count(20)->create();
        $repository = new CategoriaRepository(new Model());
        $useCase = new PaginateCategoriasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new PaginateCategoriasInput(
                page: 3,
                totalPage: 7
            )
        );
        $this->assertInstanceOf(PaginateCategoriasOutput::class, $responseUseCase);
        $this->assertEquals(3, $responseUseCase->current_page);
        $this->assertEquals(15, $responseUseCase->to);
        $this->assertEquals(20, $responseUseCase->from);
        $this->assertCount(6, $responseUseCase->items);
    }
}
