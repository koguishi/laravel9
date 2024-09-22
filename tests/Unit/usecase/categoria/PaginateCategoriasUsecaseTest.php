<?php

namespace Tests\Unit\usecase\categoria;

use core\domain\repository\CategoriaRepositoryInterface;
use core\domain\repository\PaginationInterface;
use core\usecase\categoria\PaginateCategoriasInput;
use core\usecase\categoria\PaginateCategoriasOutput;
use core\usecase\categoria\PaginateCategoriasUsecase;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Unit\usecase\UsecaseTrait;

class PaginateCategoriasUsecaseTest extends TestCase
{
    use UsecaseTrait;
    
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    private $mockPagination;

    public function testPaginateCategorias()
    {
        $register = new stdClass();
        $register->id = 'id';
        $register->nome = 'name';
        $register->descricao = 'description';
        $register->ativo = 'is_active';
        $register->criado_em = 'created_at';
        $register->updated_at = 'created_at';
        $register->deleted_at = 'created_at';

        $mockPagination = $this->mockPagination([
            $register,
        ]);

        /**
         * @var CategoriaRepositoryInterface|MockInterface $mockRepo
         */
        $mockRepo = Mockery::mock(stdClass::class, CategoriaRepositoryInterface::class);
        $mockRepo->shouldReceive('paginate')->andReturn($mockPagination);

        $input = new PaginateCategoriasInput();

        $usecase = new PaginateCategoriasUsecase($mockRepo);
        $response = $usecase->execute($input);

        $this->assertInstanceOf(PaginateCategoriasOutput::class, $response);
        $this->assertCount(1, $response->items);
        $this->assertEquals($register->id, $response->items[0]['id']);
        $this->assertEquals($register->nome, $response->items[0]['nome']);
        $this->assertEquals($register->descricao, $response->items[0]['descricao']);
        $this->assertEquals($register->ativo, $response->items[0]['ativo']);
        // $this->assertEquals($register->criado_em, $response->items[0]['criado_em']);
        // $this->assertInstanceOf(stdClass::class, $response->items[0]);
    }

    public function testListCategoriesEmpty()
    {
        $mockPagination = $this->mockPagination();

        /**
         * @var CategoriaRepositoryInterface|MockInterface $mockRepo
         */
        $mockRepo = Mockery::mock(stdClass::class, CategoriaRepositoryInterface::class);
        $mockRepo->shouldReceive('paginate')->andReturn($mockPagination);

        // $mockInputDto = Mockery::mock(ListCategoriesInputDto::class, ['filter', 'desc']);
        $input = new PaginateCategoriasInput(
            filter: 'filter',
            order: "[{'id' => 'desc'}]",
        );

        $useCase = new PaginateCategoriasUseCase($mockRepo);
        $response = $useCase->execute($input);

        $this->assertInstanceOf(PaginateCategoriasOutput::class, $response);
        $this->assertCount(0, $response->items);
    }

}