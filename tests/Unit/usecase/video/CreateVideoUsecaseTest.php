<?php

namespace Tests\Unit\usecase\video;

use core\domain\exception\NotFoundException;
use core\domain\repository\AtletaRepositoryInterface;
use core\domain\repository\CategoriaRepositoryInterface;
use core\domain\repository\VideoRepositoryInterface;
use core\usecase\interfaces\FileStorageInterface;
use core\usecase\interfaces\TransactionInterface;
use core\usecase\video\CreateVideoInput;
use core\usecase\video\CreateVideoOutput;
use core\usecase\video\CreateVideoUsecase;
use core\usecase\video\VideoEventManagerInterface;
use DateTime;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

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
