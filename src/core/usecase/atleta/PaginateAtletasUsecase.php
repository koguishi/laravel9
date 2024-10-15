<?php

namespace core\usecase\atleta;

use core\domain\repository\AtletaRepositoryInterface;

class PaginateAtletasUsecase
{
    protected AtletaRepositoryInterface $repository;

    public function __construct(AtletaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(PaginateAtletasInput $input): PaginateAtletasOutput
    {
        $atletas = $this->repository->paginate(
            filter_nome: $input->filter,
            order: $input->order,
            page: $input->page,
            perPage: $input->perPage,
        );

        return new PaginateAtletasOutput(
            items: array_map(function ($data) {
                return [
                    'id' => $data->id,
                    'nome' => $data->nome,
                    'dtNascimento' => $data->dtNascimento,
                    'criadoEm' => $data->created_at,
                ];
            }, $atletas->items()),
            total: $atletas->total(),
            current_page: $atletas->currentPage(),
            last_page: $atletas->lastPage(),
            first_page: $atletas->firstPage(),
            per_page: $atletas->perPage(),
            to: $atletas->to(),
            from: $atletas->from(),
        );
    }
}
