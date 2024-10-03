<?php

namespace Tests\Feature\usecase\atleta;

use App\Models\Atleta as Model;
use app\repository\eloquent\AtletaRepository;
use core\usecase\atleta\ListAtletasInput;
use core\usecase\atleta\ListAtletasOutput;
use core\usecase\atleta\ListAtletasUsecase;
use DateTime;
use Tests\TestCase;

class ListAtletasUsecaseTest extends TestCase
{
    public function testList()
    {
        $atletasModel = Model::factory()->count(20)->create();
        $repository = new AtletaRepository(new Model());
        $useCase = new ListAtletasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ListAtletasInput()
        );

        $this->assertInstanceOf(ListAtletasOutput::class, $responseUseCase);
        $this->assertEquals(count($atletasModel), count($responseUseCase->items));
    }

    public function testListEmpty()
    {
        $repository = new AtletaRepository(new Model());
        $useCase = new ListAtletasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ListAtletasInput()
        );

        $this->assertInstanceOf(ListAtletasOutput::class, $responseUseCase);
        $this->assertCount(0, $responseUseCase->items);
    }

    public function testListFilteredByNome()
    {
        $atletasModel = Model::factory()->count(20)->create();
        $repository = new AtletaRepository(new Model());
        $useCase = new ListAtletasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ListAtletasInput(
                filter_nome: $atletasModel[0]->nome
            )
        );

        $this->assertInstanceOf(ListAtletasOutput::class, $responseUseCase);
        $this->assertCount(1, $responseUseCase->items);
        foreach ($responseUseCase->items as $key => $item) {
            $this->assertTrue(str_contains($item['nome'], $atletasModel[0]->nome));
        }
    }

    public function testListFilteredByDtNascimento()
    {
        $arrDatas = [
            '1999-12-31',
            '2000-01-01',
        ];
        Model::factory()->create(['dtNascimento' => $arrDatas[0]]);
        Model::factory()->create(['dtNascimento' => $arrDatas[0]]);
        Model::factory()->create(['dtNascimento' => $arrDatas[1]]);
        Model::factory()->create(['dtNascimento' => $arrDatas[1]]);

        $repository = new AtletaRepository(new Model());
        $useCase = new ListAtletasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ListAtletasInput(
                filter_dtNascimento_inicial: new DateTime($arrDatas[0]),
                filter_dtNascimento_final: new DateTime($arrDatas[0]),
            )
        );
        $this->assertInstanceOf(ListAtletasOutput::class, $responseUseCase);
        $this->assertCount(2, $responseUseCase->items);

        $responseUseCase = $useCase->execute(
            new ListAtletasInput(
                filter_dtNascimento_inicial: new DateTime($arrDatas[0]),
                filter_dtNascimento_final: new DateTime($arrDatas[1]),
            )
        );
        $this->assertInstanceOf(ListAtletasOutput::class, $responseUseCase);
        $this->assertCount(4, $responseUseCase->items);
    }

    public function testListFilteredByNomeNotFound()
    {
        $atletasModel = Model::factory()->count(20)->create();
        $repository = new AtletaRepository(new Model());
        $useCase = new ListAtletasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ListAtletasInput(
                filter_nome: 'filtro qualquer 1234'
            )
        );

        $this->assertInstanceOf(ListAtletasOutput::class, $responseUseCase);
        $this->assertCount(0, $responseUseCase->items);
    }

    public function testListFilteredByDtNascimentoNotFound()
    {
        $dataExistente = '1999-12-31';
        $dataPesquisa = '1999-12-30';
        Model::factory()->create(['dtNascimento' => $dataExistente]);
        Model::factory()->create(['dtNascimento' => $dataExistente]);
        Model::factory()->create(['dtNascimento' => $dataExistente]);
        Model::factory()->create(['dtNascimento' => $dataExistente]);

        $repository = new AtletaRepository(new Model());
        $useCase = new ListAtletasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ListAtletasInput(
                filter_dtNascimento_inicial: new DateTime($dataPesquisa),
                filter_dtNascimento_final: new DateTime($dataPesquisa),
            )
        );
        $this->assertInstanceOf(ListAtletasOutput::class, $responseUseCase);
        $this->assertCount(0, $responseUseCase->items);
    }

    public function testListOrderByNomeAsc()
    {
        $atletasModel = Model::factory()->count(20)->create();
        $arrNomes = [];
        foreach ($atletasModel as $key => $atletaModel) {
            array_push($arrNomes, $atletaModel->nome);
        }
        sort($arrNomes);

        $repository = new AtletaRepository(new Model());
        $useCase = new ListAtletasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ListAtletasInput(
                order: '{"nome": "ASC"}',
            )
        );

        $this->assertInstanceOf(ListAtletasOutput::class, $responseUseCase);
        $this->assertEquals(count($atletasModel), count($responseUseCase->items));

        foreach ($responseUseCase->items as $key => $item) {
            $this->assertEquals($arrNomes[$key], $item['nome']);
        }
    }

    public function testListOrderByDtNascimentoAsc()
    {
        $atletasModel = Model::factory()->count(20)->create();
        $arrDatas = [];
        foreach ($atletasModel as $key => $atletaModel) {
            array_push($arrDatas, $atletaModel->dtNascimento);
        }
        sort($arrDatas);

        $repository = new AtletaRepository(new Model());
        $useCase = new ListAtletasUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ListAtletasInput(
                order: '{"dtNascimento": "ASC"}',
            )
        );

        $this->assertInstanceOf(ListAtletasOutput::class, $responseUseCase);
        $this->assertEquals(count($atletasModel), count($responseUseCase->items));

        foreach ($responseUseCase->items as $key => $item) {
            $this->assertEquals($arrDatas[$key]->format('Y-m-d H:i:s'), $item['dtNascimento']);
        }
    }

}
