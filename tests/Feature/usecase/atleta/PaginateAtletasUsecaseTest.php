<?php

namespace Tests\Feature\usecase\atleta;

use App\Models\Atleta as MOdel;
use app\repository\eloquent\AtletaRepository;
use core\usecase\atleta\PaginateAtletasInput;
use core\usecase\atleta\PaginateAtletasOutput;
use core\usecase\atleta\PaginateAtletasUsecase;
use Tests\TestCase;

class PaginateAtletasUsecaseTest extends TestCase
{
    public function testPaginate()
    {
        $atletasModel = Model::factory()->count(20)->create();
        $repository = new AtletaRepository(new Model());
        $useCase = new PaginateAtletasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new PaginateAtletasInput()
        );
        $this->assertInstanceOf(PaginateAtletasOutput::class, $responseUseCase);
        $this->assertCount($responseUseCase->per_page, $responseUseCase->items);
    }

    public function testPaginateTotal20PerPage7Current2()
    {
        $atletasModel = Model::factory()->count(20)->create();
        $repository = new AtletaRepository(new Model());
        $useCase = new PaginateAtletasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new PaginateAtletasInput(
                page: 2,
                totalPage: 7
            )
        );
        $this->assertInstanceOf(PaginateAtletasOutput::class, $responseUseCase);
        $this->assertEquals(2, $responseUseCase->current_page);
        $this->assertEquals(8, $responseUseCase->to);
        $this->assertEquals(14, $responseUseCase->from);
        $this->assertCount(7, $responseUseCase->items);
    }

    public function testPaginateTotal20PerPage7Current3()
    {
        $atletasModel = Model::factory()->count(20)->create();
        $repository = new AtletaRepository(new Model());
        $useCase = new PaginateAtletasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new PaginateAtletasInput(
                page: 3,
                totalPage: 7
            )
        );
        $this->assertInstanceOf(PaginateAtletasOutput::class, $responseUseCase);
        $this->assertEquals(3, $responseUseCase->current_page);
        $this->assertEquals(15, $responseUseCase->to);
        $this->assertEquals(20, $responseUseCase->from);
        $this->assertCount(6, $responseUseCase->items);
    }
}
