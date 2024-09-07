<?php

namespace Tests\Unit\app\Http\Controllers;

use App\Http\Controllers\CategoriaController;
use core\usecase\categoria\PaginateCategoriasOutput;
use core\usecase\categoria\PaginateCategoriasUsecase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Consideramos que testes unitários de Controllers não são sempre necessários
 * Desde que outros testes estejam bem montados, quais sejam:
 *  - testes unitários de outras camadas (Domain, Application)
 *  - testes de integração (feature) da Controller
 */
class CategoriaControllerTest extends TestCase
{
    public function testIndex()
    {
        /**
         * @var Request
         */
        $mockRequest = Mockery::mock(Request::class);
        $mockRequest->shouldReceive('get')->andReturn('teste');
        // $mockRequest não está sendo usado pois:
        // TypeError: core\usecase\categoria\PaginateCategoriasInput::__construct():
            // Argument #2 ($arrOrder) must be of type array, string given,
            // called in /var/www/app/Http/Controllers/CategoriaController.php on line 18        
        // ?? Precisa mesmo mockar o Request ?

        $mockOutputDto = Mockery::mock(PaginateCategoriasOutput::class, [
            [], 1, 1, 1, 1, 1, 1, 1,
        ]);
        // ?? Precisa mockar este dto ?
        // $mockOutputDto = new PaginateCategoriasOutput([], 1, 1, 1, 1, 1, 1, 1);

        /**
         * @var PaginateCategoriasUsecase|MockInterface
         */
        $mockUsecase = Mockery::mock(PaginateCategoriasUsecase::class);
        $mockUsecase->shouldReceive('execute')->andReturn($mockOutputDto);

        $controller = new CategoriaController();
        $response = $controller->index(
            // $mockRequest,
            new Request(),
            $mockUsecase
        );

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);

        $this->assertIsObject($response->resource);
        $this->assertArrayHasKey('meta', $response->additional);

        /**
         * @var PaginateCategoriasUsecase|MockInterface
         */
        $mockUseCaseSpy = Mockery::spy(PaginateCategoriasUsecase::class);
        $mockUseCaseSpy->shouldReceive('execute')->andReturn($mockOutputDto);
        $controller->index(
            // $mockRequest,
            new Request(),
            $mockUseCaseSpy
        );
        $mockUseCaseSpy->shouldHaveReceived('execute');

        Mockery::close();        
    }
}
