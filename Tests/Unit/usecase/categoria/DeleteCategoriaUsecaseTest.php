<?php

namespace Tests\Unit\usecase\categoria;

use core\domain\entity\Categoria;
use core\domain\repository\CategoriaRepositoryInterface;
use core\domain\valueobject\Uuid;
use core\usecase\categoria\DeleteCategoriaInput;
use core\usecase\categoria\DeleteCategoriaOutput;
use core\usecase\categoria\DeleteCategoriaUsecase;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class DeleteCategoriaUsecaseTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testDeleteCategoria()
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
        $mockRepo->shouldReceive('delete')->andReturn(true);

        $input = new DeleteCategoriaInput(
            id: $categoria->id(),
        );

        $usecase = new DeleteCategoriaUsecase($mockRepo);
        $response = $usecase->execute($input);

        $mockRepo->shouldHaveReceived('delete');
        $this->assertInstanceOf(DeleteCategoriaOutput::class, $response);
        $this->assertTrue($response->sucesso);
    }

    public function testDeleteCategoriaFalse()
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
        $mockRepo->shouldReceive('delete')->andReturn(false);

        $input = new DeleteCategoriaInput(
            id: Uuid::random(),
        );

        $usecase = new DeleteCategoriaUsecase($mockRepo);
        $response = $usecase->execute($input);

        $mockRepo->shouldHaveReceived('delete');
        $this->assertInstanceOf(DeleteCategoriaOutput::class, $response);
        $this->assertFalse($response->sucesso);
    }

}