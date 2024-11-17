<?php

namespace Tests\Unit\usecase\atleta;

use core\domain\repository\VideoRepositoryInterface;
use core\usecase\video\DeleteVideoInput;
use core\usecase\video\DeleteVideoOutput;
use core\usecase\video\DeleteVideoUsecase;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class DeleteVideoUsecaseTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecute()
    {
        $usecase = new DeleteVideoUsecase(
            repository: $this->mockRepository(),
        );

        $input = new DeleteVideoInput('uuid');

        $output = $usecase->execute($input);
        $this->assertInstanceOf(DeleteVideoOutput::class, $output);
    }

    private function mockRepository()
    {
        /**
         * @var VideoRepositoryInterface|MockInterface $mock
         */
        $mock = Mockery::mock(
            stdClass::class,
            VideoRepositoryInterface::class,
        );

        $mock->shouldReceive("delete")
            ->once()
            ->andReturn(true);

        return $mock;
    }}
