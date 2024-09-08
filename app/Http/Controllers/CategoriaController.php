<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaStoreRequest;
use App\Http\Resources\CategoriaResource;
use core\usecase\categoria\CreateCategoriaInput;
use core\usecase\categoria\CreateCategoriaUsecase;
use core\usecase\categoria\PaginateCategoriasInput;
use core\usecase\categoria\PaginateCategoriasUsecase;
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
    
    public function store(CategoriaStoreRequest $request, CreateCategoriaUsecase $usecase)
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
}
