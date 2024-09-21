<?php

namespace core\usecase\atleta;

use core\domain\repository\AtletaRepositoryInterface;

class ReadAtletaUsecase
{
    protected AtletaRepositoryInterface $repository;

    public function __construct(AtletaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ReadAtletaInput $input): ReadAtletaOutput
    {

        $atleta = $this->repository->read($input->id);

        return new ReadAtletaOutput(
            id: $atleta->id(),
            nome: $atleta->nome,
            dtNascimento: $atleta->dtNascimento,
            criadoEm: $atleta->criadoEm(),
        );
    }
}
