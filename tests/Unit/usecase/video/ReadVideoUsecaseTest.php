<?php

namespace Tests\Unit\usecase\video;

use core\domain\entity\Video;
use core\domain\repository\VideoRepositoryInterface;
use core\usecase\video\ReadVideoInput;
use core\usecase\video\ReadVideoOutput;
use core\usecase\video\ReadVideoUsecase;
use DateTime;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class ReadVideoUsecaseTest extends TestCase
{

    public function testExecute()
    {
        $usecase = new ReadVideoUsecase(
            repository: $this->mockRepository(),
        );

        $input = new ReadVideoInput('uuid');

        $output = $usecase->execute($input);
        $this->assertInstanceOf(ReadVideoOutput::class, $output);
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

        $mock->shouldReceive("read")
            ->once()
            ->andReturn($this->createVideoEntity());        

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

}
