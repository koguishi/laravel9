<?php

namespace Tests\Unit\usecase\video;

use core\domain\repository\VideoRepositoryInterface;
use core\usecase\video\PaginateVideosInput;
use core\usecase\video\PaginateVideosOutput;
use core\usecase\video\PaginateVideosUsecase;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Unit\usecase\UsecaseTrait;

class PaginateVideosUsecaseTest extends TestCase
{
    use UsecaseTrait;

    public function testExecute()
    {
        $usecase = new PaginateVideosUsecase(
            repository: $this->mockRepository(),
        );
        $input = new PaginateVideosInput();
        $output = $usecase->execute($input);
        $this->assertInstanceOf(PaginateVideosOutput::class, $output);
    }

    private function mockRepository()
    {
        /**
         * @var VideoRepositoryInterface|MockInterface $mock
         */
        $mock = Mockery::mock(
            stdClass::class,
            VideoRepositoryInterface::class,
        );
        $mock->shouldReceive('paginate')->andReturn($this->mockPagination());

        return $mock;
    }

}
