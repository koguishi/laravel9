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
use Symfony\Component\HttpFoundation\ParameterBag;
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
        $nomeDaCategoria = 'Teste de Categoria';
        $useCase = new CreateCategoriaUsecase($this->repository);

        $request = new CategoriaStoreRequest();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'nome' => $nomeDaCategoria,
        ]));        

        $response = $this->controller->store($request, $useCase);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->status());

        // ???
        // é necessário ?
        $content = json_decode($response->getContent());
        $categoria = $content->data;
        $this->assertEquals($nomeDaCategoria, $categoria->nome);
    }    

}
