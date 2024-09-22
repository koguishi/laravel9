<?php

namespace Tests\Unit\usecase;

use core\domain\repository\PaginationInterface;
use Mockery;
use stdClass;

trait UsecaseTrait
{
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
}
