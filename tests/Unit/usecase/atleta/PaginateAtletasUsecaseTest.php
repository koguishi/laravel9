<?php

namespace Tests\Unit\usecase\atleta;

use core\domain\repository\AtletaRepositoryInterface;
use core\usecase\atleta\PaginateAtletasInput;
use core\usecase\atleta\PaginateAtletasOutput;
use core\usecase\atleta\PaginateAtletasUsecase;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Unit\usecase\UsecaseTrait;

class PaginateAtletasUsecaseTest extends TestCase
{
    use UsecaseTrait;

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function testPaginateAtletas()
    {
        /**
         * @var AtletaRepositoryInterface|MockInterface $mockRepo
         */
        $mockRepo = Mockery::mock(stdClass::class, AtletaRepositoryInterface::class);
        $mockRepo->shouldReceive('paginate')->andReturn($this->mockPagination());

        $input = new PaginateAtletasInput();

        $usecase = new PaginateAtletasUsecase($mockRepo);
        $response = $usecase->execute($input);

        $this->assertInstanceOf(PaginateAtletasOutput::class, $response);
        $this->assertCount(0, $response->items);
    }
}