<?php

namespace Tests\Feature\http\controllers;

use App\Http\Controllers\CategoriaController;

use App\Http\Requests\CategoriaCreateRequest;
use App\Http\Requests\CategoriaUpdateRequest;
use App\Models\Categoria as CategoriaModel;
use app\repository\eloquent\CategoriaRepository;
use core\usecase\categoria\CreateCategoriaUsecase;
use core\usecase\categoria\DeleteCategoriaUsecase;
use core\usecase\categoria\PaginateCategoriasUsecase;
use core\usecase\categoria\ReadCategoriaUsecase;
use core\usecase\categoria\UpdateCategoriaUsecase;
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
        $usecase = new PaginateCategoriasUsecase($this->repository);
        $response = $this->controller->index($usecase, new Request());

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertArrayHasKey('meta', $response->additional);
    }

    public function test_create()
    {
        $nomeDaCategoria = 'Teste de Categoria';
        $useCase = new CreateCategoriaUsecase($this->repository);

        $request = new CategoriaCreateRequest();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'nome' => $nomeDaCategoria,
        ]));

        $response = $this->controller->store($useCase, $request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->status());

        // ???
        // é necessário ?
        $content = json_decode($response->getContent());
        $categoria = $content->data;
        $this->assertEquals($nomeDaCategoria, $categoria->nome);
    }

    public function test_read()
    {
        $categoria = CategoriaModel::factory()->create();

        $response = $this->controller->show(
            id: $categoria->id,
            usecase: new ReadCategoriaUsecase($this->repository),
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());

        // ???
        // é necessário ?
        $content = json_decode($response->getContent());
        $categoria = $content->data;
        $this->assertEquals($categoria->nome, $categoria->nome);
    }

    public function test_update()
    {
        $categoria = CategoriaModel::factory()->create();
        $useCase = new UpdateCategoriaUsecase($this->repository);
        $nomeAlterado = 'ALTERADO ' . $categoria->nome;

        $request = new CategoriaUpdateRequest();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'nome' => $nomeAlterado,
        ]));

        // ??? não poderia passar o id no request ?
        $response = $this->controller->update($useCase, $categoria->id, $request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());

        // ???
        // é necessário ?
        $content = json_decode($response->getContent());
        $categoria = $content->data;
        $this->assertEquals($nomeAlterado, $categoria->nome);
    }

    public function test_delete()
    {
        $categoria = CategoriaModel::factory()->create();

        $response = $this->controller->destroy(
            id: $categoria->id,
            usecase: new DeleteCategoriaUsecase($this->repository),
        );        

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->status());
    }
}
