<?php

namespace Tests\Unit\app\Http\Controllers;

use App\Http\Controllers\CategoriaController;
use core\usecase\categoria\ReadAllCategoriasInput;
use core\usecase\categoria\ReadAllCategoriasOutput;
use core\usecase\categoria\ReadAllCategoriasUsecase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class CategoriaControllerTest extends TestCase
{
    public function testIndex()
    {
        $inputDto = new ReadAllCategoriasInput(
            filter: 'filtro',
            arrOrder: ['nome' => 'ASC'],
            page: 1,
            totalPage: 15
        );        
        /**
         * @var Request
         */
        $mockRequest = Mockery::mock(Request::class);
        $mockRequest->shouldReceive('get')->andReturn('teste');
        $mockRequest->shouldReceive('input')->andReturn($inputDto);

        // $mockOutputDto = Mockery::mock(ReadAllCategoriasOutput::class, [
        //     [], 1, 1, 1, 1, 1, 1, 1,
        // ]);
        $mockOutputDto = new ReadAllCategoriasOutput([], 1, 1, 1, 1, 1, 1, 1);

        /**
         * @var ReadAllCategoriasUsecase|MockInterface
         */
        $mockUsecase = Mockery::mock(ReadAllCategoriasUsecase::class);
        $mockUsecase->shouldReceive('execute')->andReturn($mockOutputDto);

        $controller = new CategoriaController();
        $response = $controller->index($mockRequest, $mockUsecase);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);

        $this->assertIsObject($response->resource);
        $this->assertArrayHasKey('meta', $response->additional);        
    }
}
