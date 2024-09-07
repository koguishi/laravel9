<?php

namespace Tests\Feature\http\controllers;

use App\Http\Controllers\CategoriaController;

use App\Http\Requests\CategoriaStoreRequest;
use App\Models\Categoria as CategoriaModel;
use app\repository\eloquent\CategoriaRepository;
use core\usecase\categoria\CreateCategoriaUsecase;
use core\usecase\categoria\PaginateCategoriasUsecase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Tests\TestCase;

class CategoriaControllerTest extends TestCase
{
    protected $repository;

    protected $controller;

    protected function setUp(): void
    {
        $this->repository = new CategoriaRepository(
            new CategoriaModel()
        );
        $this->controller = new CategoriaController();

        parent::setUp();
    }    

    public function test_index()
    {
        $useCase = new PaginateCategoriasUsecase($this->repository);

        $response = $this->controller->index(new Request(), $useCase);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertArrayHasKey('meta', $response->additional);
    }

    public function test_store()
    {
        $useCase = new CreateCategoriaUsecase($this->repository);

        $request = new CategoriaStoreRequest();

        $response = $this->controller->store($request, $useCase);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->status());
    }    

}
