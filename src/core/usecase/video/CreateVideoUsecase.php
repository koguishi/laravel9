<?php

namespace core\usecase\video;

use core\domain\entity\Video;
use core\domain\enum\MediaStatus;
use core\domain\event\VideoCreatedEvent;
use core\domain\exception\NotFoundException;
use core\domain\repository\AtletaRepositoryInterface;
use core\domain\repository\CategoriaRepositoryInterface;
use core\domain\repository\VideoRepositoryInterface;
use core\usecase\interfaces\FileStorageInterface;
use core\usecase\interfaces\TransactionInterface;

class CreateVideoUsecase extends BaseVideoUsecase
{
    public function execute(CreateVideoInput $input): CreateVideoOutput
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

        $this->builder->createEntity($input)
            ->addCategoriasIds($input)
            ->addAtletasIds($input);

        try {
            // persitir a entity do video usando $repository
            $this->repository->create($this->builder->getEntity());

            // armazenar a media usando o id da entity do video para o path usando o $fileStorage
            $this->addVideoMedia($input);

            $this->repository->updateMedia($this->builder->getEntity());

            // comitar a transação usando o $transaction
            $this->transaction->commit();

            return $this->createOutput($this->builder->getEntity());
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
}