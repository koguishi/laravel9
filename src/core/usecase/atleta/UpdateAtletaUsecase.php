<?php

namespace core\usecase\atleta;

use core\domain\entity\Atleta;
use core\domain\repository\AtletaRepositoryInterface;

class UpdateAtletaUsecase
{
    protected AtletaRepositoryInterface $repository;

    public function __construct(AtletaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(UpdateAtletaInput $input): UpdateAtletaOutput
    {
        $atleta = $this->repository->read($input->id);

        $atleta->alterar(
            nome: $input->nome,
            dtNascimento: $input->dtNascimento,
        );

        $this->repository->update($atleta);

        return new UpdateAtletaOutput(
            id: $atleta->id(),
            nome: $atleta->nome,
            dtNascimento: $atleta->dtNascimento,
            criadoEm: $atleta->criadoEm(),
        );
    }
}
