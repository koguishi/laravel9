<?php

namespace core\usecase\atleta;

use core\domain\repository\AtletaRepositoryInterface;

class DeleteAtletaUsecase
{
    protected AtletaRepositoryInterface $repository;

    public function __construct(AtletaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(DeleteAtletaInput $input): DeleteAtletaOutput
    {
        return new DeleteAtletaOutput(
            sucesso: $this->repository->delete($input->id),
        );
    }
}
