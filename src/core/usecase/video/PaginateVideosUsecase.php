<?php

namespace core\usecase\video;

use core\domain\repository\VideoRepositoryInterface;

class PaginateVideosUsecase
{
    public function __construct(
        protected VideoRepositoryInterface $repository
    ) { }
    public function execute(PaginateVideosInput $input): PaginateVideosOutput
    {
        $videos = $this->repository->paginate(
            page: $input->page,
            perPage: $input->perPage,
            order: $input->order,
            filter: $input->filter,
            filter_dtFilmagem_inicial: $input->filter_dtNascimento_inicial,
            filter_dtFilmagem_final: $input->filter_dtNascimento_final,
        );

        return new PaginateVideosOutput(
            items: array_map(function ($data) {
                return [
                    'id' => $data->id,
                    'nome' => $data->nome,
                    'dtNascimento' => $data->dtNascimento,
                    'criadoEm' => $data->created_at,
                ];
            }, $videos->items()),
            total: $videos->total(),
            current_page: $videos->currentPage(),
            last_page: $videos->lastPage(),
            first_page: $videos->firstPage(),
            per_page: $videos->perPage(),
            to: $videos->to(),
            from: $videos->from(),
        );
    }
}