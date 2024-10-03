<?php

namespace Tests\Feature\usecase\atleta;

use App\Models\Atleta as Model;
use app\repository\eloquent\AtletaRepository;
use core\domain\exception\NotFoundException;
use core\usecase\atleta\DeleteAtletaInput;
use core\usecase\atleta\DeleteAtletaOutput;
use core\usecase\atleta\DeleteAtletaUsecase;
use Tests\TestCase;
use Throwable;

class DeleteAtletaUsecaseTest extends TestCase
{
    public function testDelete()
    {
        $atletaModel = Model::factory()->create();

        $this->assertDatabaseHas('atletas', [
            'id' => $atletaModel->id,
        ]);

        $repository = new AtletaRepository(new Model());
        $useCase = new DeleteAtletaUsecase($repository);
        $responseUseCase = $useCase->execute(
            new DeleteAtletaInput(
                id: $atletaModel->id,
            )
        );
        $this->assertInstanceOf(DeleteAtletaOutput::class, $responseUseCase);
        $this->assertTrue($responseUseCase->sucesso);

        $this->assertSoftDeleted($atletaModel);
    }

    public function testDeleteNotFound()
    {
        try {
            $repository = new AtletaRepository(new Model());
            $useCase = new DeleteAtletaUsecase($repository);
            $useCase->execute(
                new DeleteAtletaInput(
                    id: 'fake',
                )
            );

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }
}
