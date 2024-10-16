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
            page: $input->page,
            perPage: $input->perPage,
            order: $input->order,
            filter_nome: $input->filter_nome,
            filter_dtNascimento_inicial: $input->filter_dtNascimento_inicial,
            filter_dtNascimento_final: $input->filter_dtNascimento_final,
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
