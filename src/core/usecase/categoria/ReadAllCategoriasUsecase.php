<?php

namespace core\usecase\categoria;

use core\domain\repository\CategoriaRepositoryInterface;

class ReadAllCategoriasUsecase
{
    protected CategoriaRepositoryInterface $repository;

    public function __construct(CategoriaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ReadAllCategoriasInput $input): ReadAllCategoriasOutput
    {
        $categorias = $this->repository->readAll(
            filter: $input->filter,
            arrOrder: $input->arrOrder,
        );

        return new ReadAllCategoriasOutput(
            items: $categorias,
        );
    }
}
