<?php

namespace test\unit\usecase\categoria;

use core\domain\entity\Categoria;
use core\domain\repository\CategoriaRepositoryInterface;
use core\usecase\categoria\CreateCategoriaInput;
use core\usecase\categoria\CreateCategoriaOutput;
use core\usecase\categoria\CreateCategoriaUsecase;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class CreateCategoriaUsecaseTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testCreateCategoria()
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
        $mockRepo->shouldReceive('create')->andReturn($categoria);

        $input = new CreateCategoriaInput(
            nome: $categoria->nome,
            descricao: $categoria->descricao,
            ativo: $categoria->ativo,
        );

        $usecase = new CreateCategoriaUsecase($mockRepo);
        $response = $usecase->execute($input);

        $mockRepo->shouldHaveReceived('create');

        $this->assertInstanceOf(CreateCategoriaOutput::class, $response);
        $this->assertEquals($response->id, $categoria->id());
        $this->assertEquals($response->nome, $categoria->nome);
        $this->assertEquals($response->descricao, $categoria->descricao);
        $this->assertEquals($response->ativo, $categoria->ativo);
        $this->assertNotEmpty($response->criadoEm, $categoria->criadoEm());
    }

    public function testCreateCategoriaSomenteNome()
    {
        $categoria = new Categoria(
            nome: 'categoria A',
        );

        /**
         * @var CategoriaRepositoryInterface|MockInterface $mockRepo
         */
        $mockRepo = Mockery::mock(
            stdClass::class,
            CategoriaRepositoryInterface::class,
        );
        $mockRepo->shouldReceive('create')->andReturn($categoria);

        $input = new CreateCategoriaInput(
            nome: $categoria->nome,
        );

        $usecase = new CreateCategoriaUsecase($mockRepo);
        $response = $usecase->execute($input);

        $mockRepo->shouldHaveReceived('create');
        $this->assertInstanceOf(CreateCategoriaOutput::class, $response);
        $this->assertEquals($categoria->id(), $response->id);
        $this->assertEquals($categoria->nome, $response->nome);
        $this->assertEquals('', $response->descricao);
        $this->assertTrue($response->ativo);
        $this->assertNotEmpty($response->criadoEm);
    }

    public function testCreateCategoriaDesativada()
    {
        $categoria = new Categoria(
            nome: 'categoria A',
            ativo: false,
        );

        /**
         * @var CategoriaRepositoryInterface|MockInterface $mockRepo
         */
        $mockRepo = Mockery::mock(
            stdClass::class,
            CategoriaRepositoryInterface::class,
        );
        $mockRepo->shouldReceive('create')->andReturn($categoria);

        $input = new CreateCategoriaInput(
            nome: $categoria->nome,
            ativo: $categoria->ativo,
        );

        $usecase = new CreateCategoriaUsecase($mockRepo);
        $response = $usecase->execute($input);

        $mockRepo->shouldHaveReceived('create');
        $this->assertInstanceOf(CreateCategoriaOutput::class, $response);
        $this->assertEquals($categoria->id(), $response->id);
        $this->assertEquals($categoria->nome, $response->nome);
        $this->assertEquals('', $response->descricao);
        $this->assertFalse($response->ativo);
        $this->assertNotEmpty($response->criadoEm);
    }
}