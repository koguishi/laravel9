<?php

namespace Tests\Feature\usecase\atleta;

use App\Models\Atleta as Model;
use app\repository\eloquent\AtletaRepository;
use core\domain\exception\NotFoundException;
use core\usecase\atleta\UpdateAtletaInput;
use core\usecase\atleta\UpdateAtletaOutput;
use core\usecase\atleta\UpdateAtletaUsecase;
use Tests\TestCase;
use Throwable;

class UpdateAtletaUsecaseTest extends TestCase
{
    public function testUpdateNotFound()
    {
        try {
            $repository = new AtletaRepository(new Model());
            $useCase = new UpdateAtletaUsecase($repository);
            $responseUseCase = $useCase->execute(
                new UpdateAtletaInput(
                    id: 'fake',
                )
            );

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }

    public function testUpdateNome()
    {
        $atletaModel = Model::factory()->create();

        $this->assertDatabaseHas('atletas', [
            'id' => $atletaModel->id,
            'nome' => $atletaModel->nome,
        ]);

        $repository = new AtletaRepository(new Model());
        $useCase = new UpdateAtletaUsecase($repository);
        $responseUseCase = $useCase->execute(
            new UpdateAtletaInput(
                id: $atletaModel->id,
                nome: 'nome alterado',
            )
        );
        $this->assertInstanceOf(UpdateAtletaOutput::class, $responseUseCase);

        $this->assertEquals('nome alterado', $responseUseCase->nome);
        $this->assertEquals($atletaModel->dt_nascimento, $responseUseCase->dtNascimento);

        $this->assertDatabaseMissing('atletas', [
            'nome' => $atletaModel->nome,
        ]);
        $this->assertDatabaseHas('atletas', [
            'id' => $atletaModel->id,
            'nome' => $responseUseCase->nome,
        ]);

    }

    public function testUpdateDtNascimento()
    {
        $data = '1999-12-30';
        $atletaModel = Model::factory()->create(['dt_nascimento' => $data]);

        $this->assertDatabaseHas('atletas', [
            'id' => $atletaModel->id,
            'dt_nascimento' => $data,
        ]);

        $dataAlterada = '1999-12-31';

        $repository = new AtletaRepository(new Model());
        $useCase = new UpdateAtletaUsecase($repository);
        $responseUseCase = $useCase->execute(
            new UpdateAtletaInput(
                id: $atletaModel->id,
                dtNascimento: $dataAlterada,
            )
        );
        $this->assertInstanceOf(UpdateAtletaOutput::class, $responseUseCase);
        $this->assertEquals($dataAlterada, $responseUseCase->dtNascimento->format('Y-m-d'));
        $this->assertEquals($atletaModel->nome, $responseUseCase->nome);

        $this->assertDatabaseMissing('atletas', [
            'dt_nascimento' => $atletaModel->dtNascimento,
        ]);
        $this->assertDatabaseHas('atletas', [
            'id' => $atletaModel->id,
            'dt_nascimento' => $responseUseCase->dtNascimento,
        ]);

    }
}
