<?php

namespace core\usecase\atleta;

use core\domain\repository\AtletaRepositoryInterface;

class ListAtletasUsecase
{
    protected AtletaRepositoryInterface $repository;

    public function __construct(AtletaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ListAtletasInput $input): ListAtletasOutput
    {

        $atletas = $this->repository->list(
            filter_nome: $input->filter_nome,
            filter_dtNascimento_inicial: $input->filter_dtNascimento_inicial,
            filter_dtNascimento_final: $input->filter_dtNascimento_final,
            order: $input->order,
        );

        return new ListAtletasOutput(
            items: $atletas,
        );
    }
}
