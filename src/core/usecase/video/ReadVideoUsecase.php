<?php

namespace core\usecase\video;

use core\domain\entity\Video;
use core\domain\repository\VideoRepositoryInterface;

class ReadVideoUsecase
{
    public function __construct(
        protected VideoRepositoryInterface $repository
    ) { }

    public function execute(ReadVideoInput $input): ReadVideoOutput
    {
        $video = $this->repository->read($input->id);

        return new ReadVideoOutput(
            id: $video->id(),
            titulo: $video->titulo,
            descricao: $video->descricao,
            dtFilmagem: $video->dtFilmagem,
        );
    }
}