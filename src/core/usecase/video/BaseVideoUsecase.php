<?php

namespace core\usecase\video;

use core\domain\enum\MediaStatus;
use core\domain\event\VideoCreatedEvent;
use core\domain\exception\NotFoundException;
use core\domain\repository\AtletaRepositoryInterface;
use core\domain\repository\CategoriaRepositoryInterface;
use core\domain\repository\VideoRepositoryInterface;
use core\usecase\interfaces\FileStorageInterface;
use core\usecase\interfaces\TransactionInterface;

abstract class BaseVideoUsecase
{
    protected VideoBuilder $builder;
    public function __construct(
        protected VideoRepositoryInterface $repository,
        protected TransactionInterface $transaction,
        protected FileStorageInterface $fileStorage,
        protected VideoEventManagerInterface $eventManager,
        protected CategoriaRepositoryInterface $categoriaRepository,
        protected AtletaRepositoryInterface $atletaRepository,
    ) {
        $this->builder = new VideoBuilder();
    }

    protected function addVideoMedia(object $input): void
    {
        $path = $this->storeFile($this->builder->getEntity()->id(), $input->videoFile);
        if ($path) {
            // informar path para entidade video
            $this->builder->addVideoMedia($path, MediaStatus::PENDING);

            // disparar o evento usando o $eventManager
            $this->eventManager->dispatch(new VideoCreatedEvent($this->builder->getEntity()));
        }
    }

    protected function storeFile(string $path, ?array $media = null): string
    {
        if ($media) {
            $this->fileStorage->store(
                path: $path,
                file: $media,
            );
        }
        return '';
    }

    protected function validateIds($repository, string $label, array $ids = [])
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