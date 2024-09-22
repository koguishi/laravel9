<?php

namespace Tests\Unit\usecase\atleta;

use core\domain\repository\AtletaRepositoryInterface;
use core\usecase\atleta\DeleteAtletaInput;
use core\usecase\atleta\DeleteAtletaOutput;
use core\usecase\atleta\DeleteAtletaUsecase;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use stdClass;

class DeleteAtletaUsecaseTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testDeleteAtleta()
    {
        $uuid = (string) RamseyUuid::uuid4();

        /**
         * @var AtletaRepositoryInterface|MockInterface $mockRepo
         */
        $mockRepo = Mockery::mock(
            stdClass::class,
            AtletaRepositoryInterface::class,
        );
        $mockRepo->shouldReceive('delete')->andReturn(true);

        $input = new DeleteAtletaInput(
            id: $uuid
        );

        $usecase = new DeleteAtletaUsecase($mockRepo);
        $response = $usecase->execute($input);

        $mockRepo->shouldHaveReceived('delete');

        $this->assertInstanceOf(DeleteAtletaOutput::class, $response);
        $this->assertTrue($response->sucesso);
    }
}
