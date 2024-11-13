<?php

namespace Tests\Unit\usecase\video;

use core\domain\valueobject\Uuid;
use core\usecase\video\UpdateVideoInput;
use core\usecase\video\UpdateVideoOutput;
use core\usecase\video\UpdateVideoUsecase;
use DateTime;

class UpdateVideoUsecaseTest extends BaseVideoUsecaseTest
{

    public function testExecute()
    {
        $this->createUsecase();

        $response = $this->usecase->execute(
            input: $this->videoInput()
        );
        $this->assertInstanceOf(UpdateVideoOutput::class, $response);
    }

    protected function repositoryActionName(): string
    {
        return 'update';
    }

    protected function getUsecase(): string
    {
        return UpdateVideoUsecase::class;
    }

    protected function videoInput(
        string $titulo = 'titulo',
        string $descricao = 'descrição',
        DateTime $dtFilmagem = new DateTime('2001-01-01'),
        array $categoriasIds = [],
        array $atletasIds = [],
        ?array $videoFile = null,
    )
    {
        $input = new UpdateVideoInput(
            id: Uuid::random(),
            titulo: $titulo,
            descricao: $descricao,
            dtFilmagem: $dtFilmagem,
            categoriasIds: $categoriasIds,
            atletasIds: $atletasIds,
            videoFile: $videoFile,
        );

        return $input;
    }
}
