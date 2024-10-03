<?php

namespace Tests\Feature\usecase\atleta;

use App\Models\Atleta as Model;
use app\repository\eloquent\AtletaRepository;
use core\usecase\atleta\CreateAtletaInput;
use core\usecase\atleta\CreateAtletaUsecase;
use DateTime;
use Tests\TestCase;

class CreateAtletaUsecaseTest extends TestCase
{
    public function testCreate()
    {
        $repository = new AtletaRepository(new Model());
        $useCase = new CreateAtletaUsecase($repository);
        $responseUseCase = $useCase->execute(
            new CreateAtletaInput(
                nome: 'Teste',
                dtNascimento: (new DateTime())->modify('-3 days'),
            )
        );

        $this->assertEquals('Teste', $responseUseCase->nome);
        $this->assertNotEmpty($responseUseCase->id);

        $this->assertDatabaseHas('atletas', [
            'id' => $responseUseCase->id,
        ]);
    }
}
