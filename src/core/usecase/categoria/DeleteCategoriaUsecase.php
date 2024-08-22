<?php

namespace core\usecase\categoria;

use core\domain\repository\CategoriaRepositoryInterface;

class DeleteCategoriaUsecase
{
    protected CategoriaRepositoryInterface $repository;

    public function __construct(CategoriaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(DeleteCategoriaInput $input): DeleteCategoriaOutput
    {
        return new DeleteCategoriaOutput(
            sucesso: $this->repository->delete($input->id),
        );
    }
}
