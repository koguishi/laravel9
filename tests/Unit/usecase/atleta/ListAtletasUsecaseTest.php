<?php

namespace Tests\Unit\usecase\atleta;

use core\domain\entity\Atleta;
use core\domain\repository\AtletaRepositoryInterface;
use core\domain\valueobject\Uuid;
use core\usecase\atleta\ListAtletasInput;
use core\usecase\atleta\ListAtletasOutput;
use core\usecase\atleta\ListAtletasUsecase;
use DateTime;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use stdClass;

class ListAtletasUsecaseTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testListAtletas()
    {
        $uuid = (string) RamseyUuid::uuid4();
        $atleta1 = new Atleta(
            id: new Uuid($uuid),
            nome: 'Atleta A',
            dtNascimento: new DateTime('2001-01-01'),
        );
        $uuid = (string) RamseyUuid::uuid4();
        $atleta2 = new Atleta(
            id: new Uuid($uuid),
            nome: 'Atleta B',
            dtNascimento: new DateTime('2002-02-02'),
        );
        $atletas = [$atleta1, $atleta2];

        /**
         * @var AtletaRepositoryInterface|MockInterface $mockRepo
         */
        $mockRepo = Mockery::mock(
            stdClass::class,
            AtletaRepositoryInterface::class,
        );
        $mockRepo->shouldReceive('list')->andReturn($atletas);

        $input = new ListAtletasInput(
            filter: '',
            order: '',
        );

        $usecase = new ListAtletasUsecase($mockRepo);
        $response = $usecase->execute($input);

        $mockRepo->shouldHaveReceived('list');

        $this->assertInstanceOf(ListAtletasOutput::class, $response);

        $this->assertCount(2, $response->items);
        $this->assertContains($atleta1, $response->items);
        $this->assertContains($atleta2, $response->items);
    }
}
