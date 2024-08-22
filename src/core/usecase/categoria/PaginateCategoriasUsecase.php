<?php

namespace core\usecase\categoria;

use core\domain\repository\CategoriaRepositoryInterface;

class PaginateCategoriasUsecase
{
    protected CategoriaRepositoryInterface $repository;

    public function __construct(CategoriaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(PaginateCategoriasInput $input): PaginateCategoriasOutput
    {
        $categorias = $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage,
        );

        return new PaginateCategoriasOutput(
            items: array_map(function ($data) {
                return [
                    'id' => $data->id,
                    'nome' => $data->nome,
                    'descricao' => $data->descricao,
                    'ativo' => (bool) $data->ativo,
                    'criado_em' => (string) $data->criado_em,
                ];
            }, $categorias->items()),
            total: $categorias->total(),
            current_page: $categorias->currentPage(),
            last_page: $categorias->lastPage(),
            first_page: $categorias->firstPage(),
            per_page: $categorias->perPage(),
            to: $categorias->to(),
            from: $categorias->to(),
        );
    }
}
