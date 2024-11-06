<?php

namespace core\usecase\video;

use core\domain\entity\Video;
use core\domain\enum\MediaStatus;
use core\domain\event\VideoCreatedEvent;
use core\domain\exception\NotFoundException;
use core\domain\repository\AtletaRepositoryInterface;
use core\domain\repository\CategoriaRepositoryInterface;
use core\domain\repository\VideoRepositoryInterface;
use core\domain\valueobject\Media;
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
            $path = $this->storeVideo($video->id(), $input->videoFile);
            if ($path) {

                // informar path para entidade video
                $media = new Media($path, MediaStatus::PENDING);
                $video->setVideoFile($media);
                $this->repository->updateMedia($video);

                // disparar o evento usando o $eventManager
                $this->eventManager->dispatch(new VideoCreatedEvent($video));
            }

            // comitar a transação usando o $transaction
            $this->transaction->commit();

            return $this->createOutput($video);
        } catch (\Throwable $th) {
            $this->transaction->rollBack();

            // if (isset($path)) $this->fileStorage->delete($path);

            throw $th;
        }
    }

    private function createOutput(Video $video): CreateVideoOutput
    {
        return new CreateVideoOutput(
            id: $video->id(),
            titulo: $video->titulo,
            descricao: $video->descricao,
            dtFilmagem: $video->dtFilmagem,
            pathVideoFile: $video->videoFile()?->filePath
        );
    }

    private function createVideoEntity(CreateVideoInput $input): Video
    {
        $this->validateIds(
            $this->categoriaRepository,
            'Categoria(s)',
            $input->categoriasIds
        );

        $this->validateIds(
            $this->atletaRepository,
            'Atleta(s)',
            $input->atletasIds
        );

        $video = new Video(
            titulo: $input->titulo,
            descricao: $input->descricao,
            dtFilmagem: $input->dtFilmagem,
        );

        foreach ($input->categoriasIds as $categoriaId) {
            $video->vincularCategoria($categoriaId);
        }

        foreach ($input->atletasIds as $atletaId) {
            $video->vincularAtleta($atletaId);
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

    private function validateIds($repository, string $label, array $ids = [])
    {
        $idsDb = $repository->getIds($ids);

        $arrayDiff = array_diff($ids, $idsDb);

        $countDiff = count($arrayDiff);

        if ($countDiff) {
            $msg = sprintf(
                '%s not found: %s',
                $label,
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }

}