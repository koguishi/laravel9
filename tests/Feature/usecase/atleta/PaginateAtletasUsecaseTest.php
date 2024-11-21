<?php

namespace Tests\Feature\usecase\atleta;

use App\Models\Atleta as MOdel;
use app\repository\eloquent\AtletaRepository;
use core\usecase\atleta\PaginateAtletasInput;
use core\usecase\atleta\PaginateAtletasOutput;
use core\usecase\atleta\PaginateAtletasUsecase;
use DateTime;
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
                perPage: 7
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
                perPage: 7
            )
        );
        $this->assertInstanceOf(PaginateAtletasOutput::class, $responseUseCase);
        $this->assertEquals(3, $responseUseCase->current_page);
        $this->assertEquals(15, $responseUseCase->to);
        $this->assertEquals(20, $responseUseCase->from);
        $this->assertCount(6, $responseUseCase->items);
    }

    public function testPaginateFilteredByDtNascimento()
    {
        $arrDatas = [
            '1999-12-30',
            '1999-12-31',
            '2000-01-01',
            '2000-01-02',
        ];
        Model::factory(count: 10)->create(['dt_nascimento' => $arrDatas[0]]);
        Model::factory(count: 10)->create(['dt_nascimento' => $arrDatas[1]]);
        Model::factory(count: 10)->create(['dt_nascimento' => $arrDatas[2]]);
        Model::factory(count: 10)->create(['dt_nascimento' => $arrDatas[3]]);

        $repository = new AtletaRepository(new Model());
        $useCase = new PaginateAtletasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new PaginateAtletasInput(
                page: 1,
                perPage: 5,
                filter_dtNascimento_inicial: new DateTime($arrDatas[0]),
                filter_dtNascimento_final: new DateTime($arrDatas[0]),
            )
        );

        $this->assertEquals(10, $responseUseCase->total);
        $this->assertEquals(1, $responseUseCase->current_page);
        $this->assertEquals(2, $responseUseCase->last_page);
        $this->assertEquals(1, $responseUseCase->first_page);
        $this->assertEquals(5, $responseUseCase->per_page);
        $this->assertEquals(1, $responseUseCase->to);
        $this->assertEquals(5, $responseUseCase->from);

        $this->assertInstanceOf(PaginateAtletasOutput::class, $responseUseCase);
        $this->assertCount(5, $responseUseCase->items);
    }    
}
