<?php

namespace test\unit\usecase\categoria;

use core\domain\entity\Categoria;
use core\domain\repository\CategoriaRepositoryInterface;
use core\usecase\categoria\ReadAllCategoriasInput;
use core\usecase\categoria\ReadAllCategoriasOutput;
use core\usecase\categoria\ReadAllCategoriasUsecase;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class ReadAllCategoriasUsecaseTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testReadAllCategorias()
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
        $mockRepo->shouldReceive('readAll')->andReturn($categorias);

        $input = new ReadAllCategoriasInput();

        $usecase = new ReadAllCategoriasUsecase($mockRepo);
        $response = $usecase->execute($input);

        $mockRepo->shouldHaveReceived('readAll');
        $this->assertInstanceOf(ReadAllCategoriasOutput::class, $response);
        $this->assertCount(2, $response->items);
        $this->assertContains($categoriaA, $response->items);
        $this->assertContains($categoriaB, $response->items);
    }
}