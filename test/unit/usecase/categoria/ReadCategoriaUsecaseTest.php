<?php

namespace test\unit\usecase\categoria;

use core\domain\entity\Categoria;
use core\domain\repository\CategoriaRepositoryInterface;
use core\usecase\categoria\ReadCategoriaInput;
use core\usecase\categoria\ReadCategoriaOutput;
use core\usecase\categoria\ReadCategoriaUsecase;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class ReadCategoriaUsecaseTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testReadCategoria()
    {
        $categoria = new Categoria(
            nome: 'categoria A',
            descricao: 'descrição da categoria A',
        );

        /**
         * @var CategoriaRepositoryInterface|MockInterface $mockRepo
         */
        $mockRepo = Mockery::mock(
            stdClass::class,
            CategoriaRepositoryInterface::class,
        );
        $mockRepo->shouldReceive('read')->andReturn($categoria);

        $input = new ReadCategoriaInput(
            id: $categoria->id(),
        );

        $usecase = new ReadCategoriaUsecase($mockRepo);
        $response = $usecase->execute($input);

        $mockRepo->shouldHaveReceived('read');
        $this->assertInstanceOf(ReadCategoriaOutput::class, $response);
        $this->assertEquals($response->id, $categoria->id());
        $this->assertEquals($response->nome, $categoria->nome);
        $this->assertEquals($response->descricao, $categoria->descricao);
        $this->assertEquals($response->ativo, $categoria->ativo);
        $this->assertNotEmpty($response->criadoEm, $categoria->criadoEm());
    }
}