<?php

namespace core\usecase\categoria;

use core\domain\entity\Categoria;
use core\domain\repository\CategoriaRepositoryInterface;

class CreateCategoriaUsecase
{
    protected CategoriaRepositoryInterface $repository;

    public function __construct(CategoriaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CreateCategoriaInput $input): CreateCategoriaOutput
    {
        $categoria = new Categoria(
            nome: $input->nome,
            descricao: $input->descricao ?? '',
            ativo: $input->ativo ?? true,
        );
        
        $categoriaCriada = $this->repository->create($categoria);

        return new CreateCategoriaOutput(
            id: $categoriaCriada->id(),
            nome: $categoriaCriada->nome,
            descricao: $categoriaCriada->descricao,
            ativo: $categoriaCriada->ativo,
            criadoEm: $categoriaCriada->criadoEm(),
        );
    }
}
