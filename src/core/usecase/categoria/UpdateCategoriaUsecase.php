<?php

namespace core\usecase\categoria;

use core\domain\repository\CategoriaRepositoryInterface;

class UpdateCategoriaUsecase
{
    protected CategoriaRepositoryInterface $repository;

    public function __construct(CategoriaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(UpdateCategoriaInput $input): UpdateCategoriaOutput
    {
        $categoria = $this->repository->read($input->id);

        $categoria->alterar(
            nome: $input->nome ?? $categoria->nome,
            descricao: $input->descricao ?? $categoria->descricao
        );
        if (isset($input->ativo))
        {
            $input->ativo ? $categoria->ativar() : $categoria->desativar();
        }

        $categoriaAlterada = $this->repository->update($categoria);

        return new UpdateCategoriaOutput(
            id: $categoriaAlterada->id(),
            nome: $categoriaAlterada->nome,
            descricao: $categoriaAlterada->descricao,
            ativo: $categoriaAlterada->ativo,
            criadoEm: $categoriaAlterada->criadoEm(),
        );
    }
}
