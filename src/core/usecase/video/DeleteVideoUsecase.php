<?php

namespace core\usecase\video;

use core\domain\repository\VideoRepositoryInterface;

class DeleteVideoUsecase
{
    protected VideoRepositoryInterface $repository;

    public function __construct(VideoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(DeleteVideoInput $input): DeleteVideoOutput
    {
        return new DeleteVideoOutput(
            sucesso: $this->repository->delete($input->id),
        );
    }
}
