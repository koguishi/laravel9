<?php

namespace Tests\Feature\http\controllers;

use App\Http\Controllers\AtletaController;
use App\Http\Requests\AtletaCreateRequest;
use App\Http\Requests\AtletaUpdateRequest;
use App\Models\Atleta as AtletaModel;
use app\repository\eloquent\AtletaRepository;
use core\usecase\atleta\
{
    CreateAtletaUsecase,
    ReadAtletaUsecase,
    UpdateAtletaUsecase,
    DeleteAtletaUsecase,
    PaginateAtletasUsecase,
};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response; // use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;

class AtletaControllerTest extends TestCase
{
    protected $repository;
    protected $controller;

    protected function setUp(): void
    {
        $this->repository = new AtletaRepository(
            new AtletaModel()
        );
        $this->controller = new AtletaController();

        parent::setUp();
    }    

    public function test_index()
    {
        AtletaModel::factory(count: 5)->create();
        $usecase = new PaginateAtletasUsecase($this->repository);
        $response = $this->controller->index($usecase, new Request());

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertArrayHasKey('meta', $response->additional);
    }

    public function test_create()
    {
        $nome = 'Teste de Atleta';
        $dtNascimento = '1987-06-05';

        $useCase = new CreateAtletaUsecase($this->repository);

        $request = new AtletaCreateRequest();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'nome' => $nome,
            'dtNascimento' => $dtNascimento,
        ]));

        $response = $this->controller->store($useCase, $request);
        $content = json_decode($response->getContent());
        $atleta = $content->data;
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->status());
        $this->assertEquals($nome, $atleta->nome);
        $this->assertEquals($dtNascimento, $atleta->dtNascimento);
    }

    public function test_read()
    {
        $atleta = AtletaModel::factory()->create();

        $response = $this->controller->show(
            id: $atleta->id,
            usecase: new ReadAtletaUsecase($this->repository),
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());
        $content = json_decode($response->getContent());
        $atleta = $content->data;
        $this->assertEquals($atleta->nome, $atleta->nome);
    }

    public function test_update()
    {
        $atleta = AtletaModel::factory()->create();
        $useCase = new UpdateAtletaUsecase($this->repository);
        $nomeAlterado = 'ALTERADO ' . $atleta->nome;
        $dtNascimentoAlterado = $atleta->dt_nascimento;
        $dtNascimentoAlterado->modify('+1 days');

        $request = new AtletaUpdateRequest();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'nome' => $nomeAlterado,
            'dtNascimento' => $dtNascimentoAlterado->format('Y-m-d'),
        ]));

        $response = $this->controller->update($useCase, $atleta->id, $request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());
        $content = json_decode($response->getContent());
        $atletaResponse = $content->data;
        $this->assertEquals($nomeAlterado, $atletaResponse->nome);
        $this->assertEquals($dtNascimentoAlterado->format('Y-m-d'), $atletaResponse->dtNascimento);
    }

    public function test_update_apenas_nome()
    {
        $atleta = AtletaModel::factory()->create();
        $useCase = new UpdateAtletaUsecase($this->repository);
        $nomeAlterado = 'ALTERADO ' . $atleta->nome;

        $request = new AtletaUpdateRequest();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'nome' => $nomeAlterado,
        ]));

        $response = $this->controller->update($useCase, $atleta->id, $request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());
        $content = json_decode($response->getContent());
        $atletaResponse = $content->data;
        $this->assertEquals($nomeAlterado, $atletaResponse->nome);
        $this->assertEquals($atleta->dt_nascimento->format('Y-m-d'), $atletaResponse->dtNascimento);
    }

    public function test_update_apenas_dtNascimento()
    {
        $atleta = AtletaModel::factory()->create();
        $useCase = new UpdateAtletaUsecase($this->repository);
        $dtNascimentoAlterado = $atleta->dt_nascimento;
        $dtNascimentoAlterado->modify('+1 days');

        $request = new AtletaUpdateRequest();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'dtNascimento' => $dtNascimentoAlterado->format('Y-m-d'),
        ]));

        $response = $this->controller->update($useCase, $atleta->id, $request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());
        $content = json_decode($response->getContent());
        $atletaResponse = $content->data;
        $this->assertEquals($atleta->nome, $atletaResponse->nome);
        $this->assertEquals($dtNascimentoAlterado->format('Y-m-d'), $atletaResponse->dtNascimento);
    }

    public function test_delete()
    {
        $categoria = AtletaModel::factory()->create();

        $response = $this->controller->destroy(
            id: $categoria->id,
            usecase: new DeleteAtletaUsecase($this->repository),
        );

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->status());
    }
}
