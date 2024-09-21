<?php

namespace Tests\Unit\usecase\atleta;

use core\domain\entity\Atleta;
use core\domain\repository\AtletaRepositoryInterface;
use core\usecase\atleta\CreateAtletaInput;
use core\usecase\atleta\CreateAtletaOutput;
use core\usecase\atleta\CreateAtletaUsecase;
use DateTime;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class CreateAtletaUsecaseTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testCreateAtleta()
    {
        $atleta = new Atleta(
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
        $mockRepo->shouldReceive('create')->andReturn($atleta);

        $input = new CreateAtletaInput(
            nome: $atleta->nome,
            dtNascimento: $atleta->dtNascimento,
        );

        $usecase = new CreateAtletaUsecase($mockRepo);
        $response = $usecase->execute($input);

        $mockRepo->shouldHaveReceived('create');

        $this->assertInstanceOf(CreateAtletaOutput::class, $response);
        $this->assertEquals($atleta->id(), $response->id);
        $this->assertEquals($atleta->nome, $response->nome);
        $this->assertEquals($atleta->dtNascimento, $response->dtNascimento);
        $this->assertNotEmpty($atleta->criadoEm(), $response->criadoEm);
    }
}
