<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoriaResource;
use core\usecase\categoria\ReadAllCategoriasInput;
use core\usecase\categoria\ReadAllCategoriasUsecase;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(Request $request, ReadAllCategoriasUsecase $usecase)
    {
        $usecaseInput = new ReadAllCategoriasInput(
            filter: $request->input()->filter,
            arrOrder: $request->input()->arrOrder,
            page: (int) $request->input()->page,
            totalPage: (int) $request->input()->totalPage,
        );
        $response = $usecase->execute(input: $usecaseInput);

        return CategoriaResource::collection(collect($response->items));
    }
}