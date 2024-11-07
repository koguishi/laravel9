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

class CreateVideoUsecaseTest extends TestCase
{
    protected $usecase;
    protected function setUp(): void
    {
        $this->usecase = new CreateVideoUsecase(
            repository: $this->mockRepository(),
            transaction: $this->mockTransaction(),
            fileStorage: $this->mockStorage(),
            eventManager: $this->mockEventManager(),
            categoriaRepository: $this->mockCategoriaRepository(),
            atletaRepository: $this->mockAtletaRepository(),
        );
    }
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testExecute()
    {
        $response = $this->usecase->execute(
            input: $this->videoInput()
        );
        $this->assertInstanceOf(CreateVideoOutput::class, $response);
    }

    /**
     * @dataProvider dataProviderIds
     */
    public function testExceptionIds(
        array $categoriasIds,
        array $atletasIds,
    )
    {
        $this->expectException(NotFoundException::class);
        $response = $this->usecase->execute(
            input: $this->videoInput(
                categoriasIds: $categoriasIds,
                atletasIds: $atletasIds,
            )
        );
    }

    public function dataProviderIds(): array
    {
        return [
            [['uuid_categoria_1'], ['uuid_atleta_1']],
            [['uuid_categoria_1', 'uuid_categoria_2'], ['uuid_atleta_1', 'uuid_atleta_2']],
            [['uuid_c_1', 'uuid_c_2', 'uuid_c_3'], ['uuid_a_1', 'uuid_a2', 'uuid_a3']],
        ];
    }

    public function testUploadVideoFile()
    {
        $response = $this->usecase->execute(
            input: $this->videoInput(
                videoFile: ['tmp => temp/file.png']
            )
        );
        $this->assertNotNull($response->pathVideoFile);
    }

    private function mockRepository()
    {
        // return Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
        /**
         * @var VideoRepositoryInterface|MockInterface $mock
         */
        $mock = Mockery::mock(
            stdClass::class,
            VideoRepositoryInterface::class,
        );
        $mock->shouldReceive("create", "updateMedia");
        return $mock;
    }

    private function mockTransaction()
    {
        // return Mockery::mock(stdClass::class, TransactionInterface::class);
        /**
         * @var TransactionInterface|MockInterface $mock
         */
        $mock = Mockery::mock(
            stdClass::class,
            TransactionInterface::class,
        );
        $mock->shouldReceive("rollback", "commit");
        return $mock;
    }

    private function mockStorage()
    {
        // return Mockery::mock(stdClass::class, FileStorageInterface::class);
        /**
         * @var FileStorageInterface|MockInterface $mock
         */
        $mock = Mockery::mock(
            stdClass::class,
            FileStorageInterface::class,
        );
        $mock->shouldReceive("store")->andReturn('filePath');
        return $mock;
    }
    private function mockEventManager()
    {
        // return Mockery::mock(stdClass::class, VideoEventManagerInterface::class);
        /**
         * @var VideoEventManagerInterface|MockInterface $mock
         */
        $mock = Mockery::mock(
            stdClass::class,
            VideoEventManagerInterface::class,
        );
        $mock->shouldReceive("dispatch");
        return $mock;
    }

    private function mockCategoriaRepository()
    {
        /**
         * @var CategoriaRepositoryInterface|MockInterface $mock
         */
        $mock = Mockery::mock(
            stdClass::class,
            CategoriaRepositoryInterface::class,
        );
        $mock->shouldReceive("getIds");
        return $mock;
    }

    private function mockAtletaRepository()
    {
        /**
         * @var AtletaRepositoryInterface|MockInterface $mock
         */
        $mock = Mockery::mock(
            stdClass::class,
            AtletaRepositoryInterface::class,
        );
        $mock->shouldReceive("getIds");
        return $mock;
    }

    private function videoInput(
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
