<?php

namespace core\usecase\video;

use core\domain\entity\Video;
use core\domain\event\VideoCreatedEvent;
use core\domain\repository\AtletaRepositoryInterface;
use core\domain\repository\CategoriaRepositoryInterface;
use core\domain\repository\VideoRepositoryInterface;
use core\usecase\interfaces\FileStorageInterface;
use core\usecase\interfaces\TransactionInterface;

class CreateVideoUsecase
{
    public function __construct(
        protected VideoRepositoryInterface $repository,
        protected TransactionInterface $transaction,
        protected FileStorageInterface $fileStorage,
        protected VideoEventManagerInterface $eventManager,
        protected CategoriaRepositoryInterface $categoriaRepository,
        protected AtletaRepositoryInterface $atletaRepository,
    ) { }

    public function execute(CreateVideoInput $input): CreateVideoOutput
    {
        // create entity do video usando o $input
        // inserir ids de atleta(s) e categoria(s)
        $video = $this->createVideoEntity($input);

        try {
            // persitir a entity do video usando $repository
            $this->repository->create($video);

            // armazenar a media usando o id da entity do video para o path usando o $fileStorage
            $path = $this->storeVideo($video->id(), $input->videoMedia);
            if ($path) {
                // TODO: informar path para entidade video
                // $video->alterarPath()
                // disparar o evento usando o $eventManager
                $this->eventManager->dispatch(new VideoCreatedEvent($video));
            }

            // comitar a transação usando o $transaction
            $this->transaction->commit();

            return new CreateVideoOutput(
                titulo: $input->titulo,
                descricao: $input->descricao,
                dtFilmagem: $input->dtFilmagem,
            );
        } catch (\Throwable $th) {
            $this->transaction->rollBack();
            throw $th;
        }
    }

    private function createVideoEntity(CreateVideoInput $input): Video
    {
        $video = new Video(
            titulo: $input->titulo,
            descricao: $input->descricao,
            dtFilmagem: $input->dtFilmagem,
        );

        foreach ($input->atletasIds as $atletaId) {
            $video->desvincularAtleta($atletaId);
        }

        return $video;
    }

    private function storeVideo(string $path, ?array $media = null): string
    {
        if ($media) {
            $this->fileStorage->store(
                path: $path,
                file: $media,
            );
        }
        return '';
    }
}