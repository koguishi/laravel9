<?php

namespace Tests\Unit\usecase\atleta;

use core\domain\entity\Atleta;
use core\domain\repository\AtletaRepositoryInterface;
use core\domain\valueobject\Uuid;
use core\usecase\atleta\ReadAtletaInput;
use core\usecase\atleta\ReadAtletaOutput;
use core\usecase\atleta\ReadAtletaUsecase;
use DateTime;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use stdClass;

class ReadAtletaUsecaseTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testReadAtleta()
    {
        $uuid = (string) RamseyUuid::uuid4();
        $atleta = new Atleta(
            id: new Uuid($uuid),
            nome: 'Nome do Atleta',
            dtNascimento: new DateTime('2001-01-01'),
        );

        /**
         * @var AtletaRepositoryInterface|MockInterface $mockRepo
         */
        $mockRepo = Mockery::mock(
            stdClass::class,
            AtletaRepositoryInterface::class,
        );
        $mockRepo->shouldReceive('read')->andReturn($atleta);

        $input = new ReadAtletaInput(
            id: $uuid
        );

        $usecase = new ReadAtletaUsecase($mockRepo);
        $response = $usecase->execute($input);

        $mockRepo->shouldHaveReceived('read');

        $this->assertInstanceOf(ReadAtletaOutput::class, $response);
        $this->assertEquals($atleta->id(), $response->id);
        $this->assertEquals($atleta->nome, $response->nome);
        $this->assertEquals($atleta->dtNascimento, $response->dtNascimento);
        $this->assertNotEmpty($atleta->criadoEm(), $response->criadoEm);
    }
}
