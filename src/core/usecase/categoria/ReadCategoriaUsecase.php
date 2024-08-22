<?php

namespace core\usecase\categoria;

use core\domain\repository\CategoriaRepositoryInterface;

class ReadCategoriaUsecase
{
    protected CategoriaRepositoryInterface $repository;

    public function __construct(CategoriaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ReadCategoriaInput $input): ReadCategoriaOutput
    {
        $categoria = $this->repository->read($input->id);

        return new ReadCategoriaOutput(
            id: $categoria->id(),
            nome: $categoria->nome,
            descricao: $categoria->descricao,
            ativo: $categoria->ativo,
            criadoEm: $categoria->criadoEm(),
        );
    }
}
