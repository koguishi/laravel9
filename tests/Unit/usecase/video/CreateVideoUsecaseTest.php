<?php

namespace Tests\Unit\usecase\video;

use core\usecase\video\CreateVideoInput;
use core\usecase\video\CreateVideoOutput;
use core\usecase\video\CreateVideoUsecase;
use DateTime;

class CreateVideoUsecaseTest extends BaseVideoUsecaseTest
{

    public function testExecute()
    {
        $this->createUsecase();

        $response = $this->usecase->execute(
            input: $this->videoInput()
        );
        $this->assertInstanceOf(CreateVideoOutput::class, $response);
    }

    protected function repositoryActionName(): string
    {
        return 'create';
    }

    protected function getUsecase(): string
    {
        return CreateVideoUsecase::class;
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
        $input = new CreateVideoInput(
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
