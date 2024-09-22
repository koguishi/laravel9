<?php

namespace core\usecase\categoria;

use core\domain\repository\CategoriaRepositoryInterface;

class ListCategoriasUsecase
{
    protected CategoriaRepositoryInterface $repository;

    public function __construct(CategoriaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ListCategoriasInput $input): ListCategoriasOutput
    {
        $categorias = $this->repository->list(
            filter: $input->filter,
            order: $input->order,
        );

        return new ListCategoriasOutput(
            items: $categorias,
        );
    }
}
