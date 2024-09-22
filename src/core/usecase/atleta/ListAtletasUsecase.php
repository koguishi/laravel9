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
            filter: $input->filter,
            order: $input->order,
        );

        return new ListAtletasOutput(
            items: $atletas,
        );
    }
}