<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaCreateRequest;
use App\Http\Requests\CategoriaUpdateRequest;
use App\Http\Resources\CategoriaResource;
use core\usecase\categoria\CreateCategoriaInput;
use core\usecase\categoria\CreateCategoriaUsecase;
use core\usecase\categoria\DeleteCategoriaInput;
use core\usecase\categoria\DeleteCategoriaUsecase;
use core\usecase\categoria\PaginateCategoriasInput;
use core\usecase\categoria\PaginateCategoriasUsecase;
use core\usecase\categoria\ReadCategoriaInput;
use core\usecase\categoria\ReadCategoriaUsecase;
use core\usecase\categoria\UpdateCategoriaInput;
use core\usecase\categoria\UpdateCategoriaUsecase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoriaController extends Controller
{
    public function index(PaginateCategoriasUsecase $usecase, Request $request)
    {
        $ordem = $request->get('arrOrder', '');
        $arrOrdem = json_decode($ordem);

        $usecaseInput = new PaginateCategoriasInput(
            filter: $request->get('filter', '') ,
            order: $arrOrdem,
            page: (int) $request->get('page', 1),
            totalPage: (int) $request->get('totalPage', 15),
        );
        $response = $usecase->execute(input: $usecaseInput);

        $resource = CategoriaResource::collection(collect($response->items));
        $resource->additional([
            'meta' => [
                'total' => $response->total,
                'current_page' => $response->current_page,
                'last_page' => $response->last_page,
                'first_page' => $response->first_page,
                'per_page' => $response->per_page,
                'to' => $response->to,
                'from' => $response->from,
            ],
        ]);
        return $resource;
    }
    
    public function store(CreateCategoriaUsecase $usecase, CategoriaCreateRequest $request)
    {
        $input = new CreateCategoriaInput(
            nome: $request->nome,
            descricao: $request->descricao,
            ativo: $request->ativo,
        );
        $response = $usecase->execute($input);
        // dump($response);
        $resource = new CategoriaResource($response);
        // dump($resource);

        return ($resource)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
        // é o mesmo que:
        // return new JsonResponse(
        //     data: $resource,
        //     status: Response::HTTP_CREATED,
        // );
    }

    public function show(ReadCategoriaUsecase $usecase, string $id)
    {
        $response = $usecase->execute(new ReadCategoriaInput(id: $id));

        return (new CategoriaResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function update(
        UpdateCategoriaUsecase $usecase,
        string $id,
        CategoriaUpdateRequest $request,
    )
    {
        $input = new UpdateCategoriaInput(
            id: $id,
            nome: $request->nome,
            descricao: $request->descricao,
            ativo: $request->ativo,
        );
        $response = $usecase->execute($input);
        $resource = new CategoriaResource($response);

        return ($resource)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(DeleteCategoriaUsecase $usecase, string $id)
    {
        $usecase->execute(new DeleteCategoriaInput(id: $id));
        return response()->noContent();        
    }
}
