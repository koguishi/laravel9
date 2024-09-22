<?php

namespace Tests\Unit\usecase\categoria;

use core\domain\entity\Categoria;
use core\domain\repository\CategoriaRepositoryInterface;
use core\usecase\categoria\ListCategoriasInput;
use core\usecase\categoria\ListCategoriasOutput;
use core\usecase\categoria\ListCategoriasUsecase;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class ListCategoriasUsecaseTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testListCategorias()
    {
        $categoriaA = new Categoria(
            nome: 'categoria A',
            descricao: 'descrição da categoria A',
        );
        $categoriaB = new Categoria(
            nome: 'categoria B',
            descricao: 'descrição da categoria B',
        );
        $categorias = array($categoriaA, $categoriaB);

        /**
         * @var CategoriaRepositoryInterface|MockInterface $mockRepo
         */
        $mockRepo = Mockery::mock(
            stdClass::class,
            CategoriaRepositoryInterface::class,
        );
        $mockRepo->shouldReceive('list')->andReturn($categorias);

        $input = new ListCategoriasInput();

        $usecase = new ListCategoriasUsecase($mockRepo);
        $response = $usecase->execute($input);

        $mockRepo->shouldHaveReceived('list');
        $this->assertInstanceOf(ListCategoriasOutput::class, $response);
        $this->assertCount(2, $response->items);
        $this->assertContains($categoriaA, $response->items);
        $this->assertContains($categoriaB, $response->items);
    }
}