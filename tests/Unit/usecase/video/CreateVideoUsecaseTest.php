<?php

namespace Tests\Unit\usecase\video;

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
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testConstructor()
    {
        $usecase = new CreateVideoUsecase(
            repository: $this->mockRepository(),
            transaction: $this->mockTransaction(),
            fileStorage: $this->mockStorage(),
            eventManager: $this->mockEventManager(),
        );
        $this->assertTrue(true);
    }

    public function testExecute()
    {
        $usecase = new CreateVideoUsecase(
            repository: $this->mockRepository(),
            transaction: $this->mockTransaction(),
            fileStorage: $this->mockStorage(),
            eventManager: $this->mockEventManager(),
        );
        $response = $usecase->execute(
            input: $this->videoInput()
        );
        $this->assertInstanceOf(CreateVideoOutput::class, $response);
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
        return $mock;
    }

    private function videoInput()
    {
        $input = new CreateVideoInput(
            titulo: 'titulo',
            descricao: 'descricao',
            dtFilmagem: new DateTime('2001-01-01'),
        );
        return $input;
    }
}
