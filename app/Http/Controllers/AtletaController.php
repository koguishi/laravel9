<?php

namespace App\Http\Controllers;

use App\Http\Requests\AtletaCreateRequest;
use App\Http\Requests\AtletaUpdateRequest;
use App\Http\Resources\AtletaResource;
use core\usecase\atleta\CreateAtletaInput;
use core\usecase\atleta\CreateAtletaUsecase;
use core\usecase\atleta\DeleteAtletaInput;
use core\usecase\atleta\DeleteAtletaUsecase;
use core\usecase\atleta\PaginateAtletasInput;
use core\usecase\atleta\PaginateAtletasUsecase;
use core\usecase\atleta\ReadAtletaInput;
use core\usecase\atleta\ReadAtletaUsecase;
use core\usecase\atleta\UpdateAtletaInput;
use core\usecase\atleta\UpdateAtletaUsecase;
use DateTime;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response; // use Illuminate\Http\Response;

class AtletaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection $resource
     */
    public function index(PaginateAtletasUsecase $usecase, Request $request)
    {
        $usecaseInput = new PaginateAtletasInput(
            filter: $request->get('filter', '') ,
            order: $request->get('order', ''),
            page: (int) $request->get('page', 1),
            totalPage: (int) $request->get('totalPage', 15),
        );
        $response = $usecase->execute(input: $usecaseInput);

        $resource = AtletaResource::collection(collect($response->items));
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

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateAtletaUsecase $usecase, AtletaCreateRequest $request)
    {
        $input = new CreateAtletaInput(
            nome: $request->nome,
            dtNascimento: new DateTime($request->dtNascimento),
        );

        $response = $usecase->execute($input);

        $resource = AtletaResource::make($response);
        // mesma coisa que
        // $resource = new AtletaResource($response);

        return $resource
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ReadAtletaUsecase $usecase, string $id)
    {
        $input = new ReadAtletaInput(
            id: $id
        );

        $response = $usecase->execute($input);

        $resource = AtletaResource::make($response);
        // mesma coisa que
        // $resource = new AtletaResource($response);

        return $resource
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateAtletaUsecase $usecase, string $id, AtletaUpdateRequest $request)
    {
        $input = new UpdateAtletaInput(
            id: $id,
            nome: $request->nome,
            dtNascimento: $request->dtNascimento,
        );

        $response = $usecase->execute($input);

        $resource = AtletaResource::make($response);
        // mesma coisa que
        // $resource = new AtletaResource($response);

        return $resource
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteAtletaUsecase $usecase, string $id)
    {
        $usecase->execute(new DeleteAtletaInput(id: $id));
        return response()->noContent();        
    }
}
