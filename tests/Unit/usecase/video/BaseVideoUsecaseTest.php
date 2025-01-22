<?php

namespace Tests\Unit\usecase\video;

use core\domain\entity\Video;
use core\domain\exception\NotFoundException;
use core\domain\repository\AtletaRepositoryInterface;
use core\domain\repository\CategoriaRepositoryInterface;
use core\domain\repository\VideoRepositoryInterface;
use core\usecase\interfaces\FileStorageInterface;
use core\usecase\interfaces\TransactionInterface;
use core\usecase\video\CreateVideoOutput;
use core\usecase\video\VideoEventManagerInterface;
use DateTime;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

abstract class BaseVideoUsecaseTest extends TestCase
{
    protected $usecase;

    abstract protected function repositoryActionName(): string;
    abstract protected function getUsecase(): string;
    abstract protected function videoInput(
        string $titulo = 'titulo',
        string $descricao = 'descrição',
        DateTime $dtFilmagem = new DateTime('2001-01-01'),        
        array $categoriasIds = [],
        array $atletasIds = [],
        ?array $videoFile = null,
    );

    protected function createUsecase(
        int $timesAction = 1,
        int $timesUpdateMedia = 1,
        int $timesCommit = 1,
        int $timesRollback = 0,
        int $timesStore = 0,
        int $timesDispatch = 0,
    ): void
    {
        $this->usecase = new ($this->getUsecase())(
            repository: $this->mockRepository(
                timesAction: $timesAction,
                timesUpdateMedia: $timesUpdateMedia,
            ),
            transaction: $this->mockTransaction(
                timesCommit: $timesCommit,
                timesRollback: $timesRollback,
            ),
            fileStorage: $this->mockStorage(timesStore: $timesStore),
            eventManager: $this->mockEventManager(timesDispatch: $timesDispatch),
            categoriaRepository: $this->mockCategoriaRepository(),
            atletaRepository: $this->mockAtletaRepository(),
        );
    }
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecute()
    {
        $this->createUsecase();

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
        $this->createUsecase(
            timesAction: 0,
            timesUpdateMedia: 0,
            timesCommit: 0,
            timesStore: 0,
            timesDispatch: 0,
        );

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
        $this->createUsecase(timesStore: 1, timesDispatch: 1);
        $response = $this->usecase->execute(
            input: $this->videoInput(
                videoFile: ['tmp => temp/file.png']
            )
        );
        $this->assertNotNull($response->pathVideoFile);
    }

    private function mockRepository(
        int $timesAction,
        int $timesUpdateMedia,
    )
    {
        // return Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
        /**
         * @var VideoRepositoryInterface|MockInterface $mock
         */
        $mock = Mockery::mock(
            stdClass::class,
            VideoRepositoryInterface::class,
        );
        $mock->shouldReceive($this->repositoryActionName())
            ->times($timesAction)
            ->andReturn($this->createVideoEntity());

        $mock->shouldReceive("read")
            ->andReturn($this->createVideoEntity());
        
        $mock->shouldReceive("updateMedia")
            ->zeroOrMoreTimes();

        return $mock;
    }

    private function createVideoEntity()
    {
        return new Video(
            titulo: 'Título',
            descricao: 'Descrição',
            dtFilmagem: new DateTime('2024-10-01'),
        );
    }

    private function mockTransaction(
        int $timesCommit,
        int $timesRollback,
    )
    {
        // return Mockery::mock(stdClass::class, TransactionInterface::class);
        /**
         * @var TransactionInterface|MockInterface $mock
         */
        $mock = Mockery::mock(
            stdClass::class,
            TransactionInterface::class,
        );
        $mock->shouldReceive("rollback")->times($timesRollback);
        $mock->shouldReceive("commit")->times($timesCommit);
        return $mock;
    }

    private function mockStorage(
        int $timesStore,
    )
    {
        // return Mockery::mock(stdClass::class, FileStorageInterface::class);
        /**
         * @var FileStorageInterface|MockInterface $mock
         */
        $mock = Mockery::mock(
            stdClass::class,
            FileStorageInterface::class,
        );
        $mock->shouldReceive("store")
            ->times($timesStore)
            ->andReturn('filePath');
        return $mock;
    }
    private function mockEventManager(
        int $timesDispatch = 1,
    )
    {
        // return Mockery::mock(stdClass::class, VideoEventManagerInterface::class);
        /**
         * @var VideoEventManagerInterface|MockInterface $mock
         */
        $mock = Mockery::mock(
            stdClass::class,
            VideoEventManagerInterface::class,
        );
        $mock->shouldReceive("dispatch")->times($timesDispatch);
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
}
