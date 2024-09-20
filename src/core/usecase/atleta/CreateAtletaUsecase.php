<?php

namespace core\usecase\atleta;

use core\domain\entity\Atleta;
use core\domain\repository\AtletaRepositoryInterface;

class CreateAtletaUsecase
{
    protected AtletaRepositoryInterface $repository;

    public function __construct(AtletaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CreateAtletaInput $input): CreateAtletaOutput
    {

        $atleta = new Atleta(
            nome: $input->nome,
            dtNascimento: $input->dtNascimento,
        );

        $atletaCriado = $this->repository->create($atleta);

        return new CreateAtletaOutput(
            id: $atletaCriado->id(),
            nome: $atletaCriado->nome,
            dtNascimento: $atletaCriado->dtNascimento,
            criadoEm: $atletaCriado->criadoEm(),
        );
    }
}
