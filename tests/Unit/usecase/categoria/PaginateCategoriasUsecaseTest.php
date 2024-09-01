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

class PaginateCategoriasUsecaseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    private $mockPagination;

    protected function mockPagination(array $items = [])
    {
        $this->mockPagination = Mockery::mock(stdClass::class, PaginationInterface::class);
        $this->mockPagination->shouldReceive('items')->andReturn($items);
        $this->mockPagination->shouldReceive('total')->andReturn(51);
        $this->mockPagination->shouldReceive('currentPage')->andReturn(3);
        $this->mockPagination->shouldReceive('firstPage')->andReturn(1);
        $this->mockPagination->shouldReceive('lastPage')->andReturn(6);
        $this->mockPagination->shouldReceive('perPage')->andReturn(10);
        $this->mockPagination->shouldReceive('to')->andReturn(4);
        $this->mockPagination->shouldReceive('from')->andReturn(2);

        return $this->mockPagination;
    }    

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
        $this->assertEquals($register->criado_em, $response->items[0]['criado_em']);
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
            arrOrder: ['id' => 'desc'],
        );

        $useCase = new PaginateCategoriasUseCase($mockRepo);
        $response = $useCase->execute($input);

        $this->assertInstanceOf(PaginateCategoriasOutput::class, $response);
        $this->assertCount(0, $response->items);
    }

}