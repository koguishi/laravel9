<?php

namespace Tests\Feature\usecase\atleta;

use App\Models\Atleta as Model;
use app\repository\eloquent\AtletaRepository;
use core\domain\exception\NotFoundException;
use core\usecase\atleta\ReadAtletaInput;
use core\usecase\atleta\ReadAtletaOutput;
use core\usecase\atleta\ReadAtletaUsecase;
use Tests\TestCase;
use Throwable;

class ReadAtletaUsecaseTest extends TestCase
{
    public function testRead()
    {
        $atletaModel = Model::factory()->create();
        $repository = new AtletaRepository(new Model());
        $useCase = new ReadAtletaUsecase($repository);
        $responseUseCase = $useCase->execute(
            new ReadAtletaInput(
                id: $atletaModel->id,
            )
        );

        $this->assertInstanceOf(ReadAtletaOutput::class, $responseUseCase);
        $this->assertEquals($atletaModel->nome, $responseUseCase->nome);
    }

    public function testReadNotFound()
    {
        try {
            $repository = new AtletaRepository(new Model());
            $useCase = new ReadAtletaUsecase($repository);
            $responseUseCase = $useCase->execute(
                new ReadAtletaInput(
                    id: 'fake',
                )
            );

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }
}
