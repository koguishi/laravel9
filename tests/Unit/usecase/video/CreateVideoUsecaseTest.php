<?php

namespace Tests\Unit\usecase\video;

use core\domain\repository\VideoRepositoryInterface;
use core\usecase\interfaces\FileStorageInterface;
use core\usecase\interfaces\TransactionInterface;
use core\usecase\video\CreateVideoUsecase;
use core\usecase\video\VideoEventManagerInterface;
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

    public function testCreateVideo()
    {
        $usecase = new CreateVideoUsecase(
            repository: $this->mockRepository(),
            transaction: $this->mockTransaction(),
            fileStorage: $this->mockStorage(),
            eventManager: $this->mockEventManager(),
        );
        $this->assertTrue(true);
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
}
