<?php

namespace test\unit\usecase\categoria;

use core\domain\entity\Categoria;
use core\domain\repository\CategoriaRepositoryInterface;
use core\usecase\categoria\UpdateCategoriaInput;
use core\usecase\categoria\UpdateCategoriaOutput;
use core\usecase\categoria\UpdateCategoriaUsecase;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class UpdateCategoriaUsecaseTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testUpdateCategoria()
    {
        $uuid = (string) Uuid::uuid4()->toString();

        $categoriaBD = new Categoria(
            id: $uuid,
            nome: 'Nome AAA',
            descricao: 'Descricao AAA',
            ativo: true,
        );
        $categoriaAlterada = new Categoria(
            id: $uuid,
            nome: 'Nome BBB',
            descricao: 'Descricao BBB',
            ativo: false,
        );

        /**
         * @var CategoriaRepositoryInterface|MockInterface $mockRepo
         */
        $mockRepo = Mockery::mock(stdClass::class, CategoriaRepositoryInterface::class);
        $mockRepo->shouldReceive('read')->andReturn($categoriaBD);
        $mockRepo->shouldReceive('update')->andReturn($categoriaAlterada);

        $input = new UpdateCategoriaInput(
            id: $categoriaAlterada->id(),
            nome: $categoriaAlterada->nome,
            descricao: $categoriaAlterada->descricao,
            ativo: $categoriaAlterada->ativo,
        );

        $useCase = new UpdateCategoriaUsecase($mockRepo);
        $response = $useCase->execute($input);

        $mockRepo->shouldHaveReceived('update');
        $this->assertInstanceOf(UpdateCategoriaOutput::class, $response);
        $this->assertEquals($input->nome, $response->nome);
        $this->assertEquals($input->descricao, $response->descricao);
        $this->assertEquals($input->ativo, $response->ativo);
    }

    public function testUpdateCategoriaSomenteNome()
    {
        $uuid = (string) Uuid::uuid4()->toString();

        $categoriaBD = new Categoria(
            id: $uuid,
            nome: 'Nome AAA',
            descricao: 'Descricao AAA',
            ativo: false,
        );
        $categoriaAlterada = new Categoria(
            id: $uuid,
            nome: 'Nome BBB',
            descricao: 'Descricao AAA',
            ativo: false,
        );

        /**
         * @var CategoriaRepositoryInterface|MockInterface $mockRepo
         */
        $mockRepo = Mockery::mock(stdClass::class, CategoriaRepositoryInterface::class);
        $mockRepo->shouldReceive('read')->andReturn($categoriaBD);
        $mockRepo->shouldReceive('update')->andReturn($categoriaAlterada);

        $input = new UpdateCategoriaInput(
            id: $categoriaAlterada->id(),
            nome: $categoriaAlterada->nome,
        );

        $useCase = new UpdateCategoriaUsecase($mockRepo);
        $response = $useCase->execute($input);

        $mockRepo->shouldHaveReceived('update');
        $this->assertInstanceOf(UpdateCategoriaOutput::class, $response);
        $this->assertEquals($response->nome, $input->nome);
        $this->assertEquals($response->descricao, $categoriaBD->descricao);
        $this->assertEquals($response->ativo, $categoriaBD->ativo);
    }

    public function testUpdateCategoriaSomenteDescricao()
    {
        $uuid = (string) Uuid::uuid4()->toString();

        $categoriaBD = new Categoria(
            id: $uuid,
            nome: 'Nome AAA',
            descricao: 'Descricao AAA',
            ativo: false,
        );
        $categoriaAlterada = new Categoria(
            id: $uuid,
            nome: 'Nome AAA',
            descricao: 'Descricao BBB',
            ativo: false,
        );

        /**
         * @var CategoriaRepositoryInterface|MockInterface $mockRepo
         */
        $mockRepo = Mockery::mock(stdClass::class, CategoriaRepositoryInterface::class);
        $mockRepo->shouldReceive('read')->andReturn($categoriaBD);
        $mockRepo->shouldReceive('update')->andReturn($categoriaAlterada);

        $input = new UpdateCategoriaInput(
            id: $categoriaAlterada->id(),
            descricao: $categoriaAlterada->descricao,
        );

        $useCase = new UpdateCategoriaUsecase($mockRepo);
        $response = $useCase->execute($input);

        $mockRepo->shouldHaveReceived('update');
        $this->assertInstanceOf(UpdateCategoriaOutput::class, $response);
        $this->assertEquals($response->nome, $categoriaBD->nome);
        $this->assertEquals($response->descricao, $input->descricao);
        $this->assertEquals($response->ativo, $categoriaBD->ativo);
    }
}