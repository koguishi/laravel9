<?php

namespace Tests\Unit\usecase\atleta;

use core\domain\entity\Atleta;
use core\domain\repository\AtletaRepositoryInterface;
use core\domain\valueobject\Uuid;
use core\usecase\atleta\UpdateAtletaInput;
use core\usecase\atleta\UpdateAtletaOutput;
use core\usecase\atleta\UpdateAtletaUsecase;
use DateTime;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use stdClass;

class UpdateAtletaUsecaseTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testUpdateAtleta()
    {
        $uuid = (string) RamseyUuid::uuid4();
        $atleta = new Atleta(
            id: new Uuid($uuid),
            nome: 'Nome do Atleta',
            dtNascimento: new DateTime('2001-01-01'),
        );

        $atleta = new Atleta(
            id: new Uuid($uuid),
            nome: 'Atleta Alterado',
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
        $mockRepo->shouldReceive('update')->andReturn($atleta);

        $input = new UpdateAtletaInput(
            id: $atleta->id,
            nome: $atleta->nome,
            dtNascimento: $atleta->dtNascimento,
        );

        $usecase = new UpdateAtletaUsecase($mockRepo);
        $response = $usecase->execute($input);

        $mockRepo->shouldHaveReceived('update');

        $this->assertInstanceOf(UpdateAtletaOutput::class, $response);
        $this->assertEquals($atleta->id(), $response->id);
        $this->assertEquals($atleta->nome, $response->nome);
        $this->assertEquals($atleta->dtNascimento, $response->dtNascimento);
        $this->assertNotEmpty($atleta->criadoEm(), $response->criadoEm);
    }
}
