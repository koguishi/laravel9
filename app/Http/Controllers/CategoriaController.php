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
    public function index(Request $request, PaginateCategoriasUsecase $usecase)
    {
        $usecaseInput = new PaginateCategoriasInput(
            filter: $request->get('filter', '') ,
            arrOrder: $request->get('arrOrder', []),
            page: (int) $request->get('page', 1),
            totalPage: (int) $request->get('totalPage', 15),
        );
        $response = $usecase->execute(input: $usecaseInput);

        return CategoriaResource::collection(collect($response->items))
            ->additional([
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
    }
    
    public function create(CategoriaCreateRequest $request, CreateCategoriaUsecase $usecase)
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

    public function read(string $id, ReadCategoriaUsecase $usecase)
    {
        $input = new ReadCategoriaInput(id: $id);
        $response = $usecase->execute($input);
        // dump($response); die;
        $resource = new CategoriaResource($response);
        // dump($resource); die;

        return ($resource)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
        // é o mesmo que:
        // return new JsonResponse(
        //     data: $resource,
        //     status: Response::HTTP_CREATED,
        // );
    }

    public function update(CategoriaUpdateRequest $request, UpdateCategoriaUsecase $usecase)
    {
        $input = new UpdateCategoriaInput(
            id: $request->id,
            nome: $request->nome,
            descricao: $request->descricao,
            ativo: $request->ativo,
        );
        $response = $usecase->execute($input);
        // dump($response); die;
        $resource = new CategoriaResource($response);
        // dump($resource); die;

        return ($resource)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
        // é o mesmo que:
        // return new JsonResponse(
        //     data: $resource,
        //     status: Response::HTTP_CREATED,
        // );
    }

    public function delete(string $id, DeleteCategoriaUsecase $usecase)
    {
        $usecase->execute(new DeleteCategoriaInput(id: $id));
        return response()->noContent();        
    }
}
