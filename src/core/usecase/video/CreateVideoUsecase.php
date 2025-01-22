<?php

namespace core\usecase\video;

use core\domain\builder\CreateVideoBuilder;
use core\domain\builder\VideoBuilderInterface;
use core\domain\entity\Video;

class CreateVideoUsecase extends BaseVideoUsecase
{
    protected function getBuilder(): VideoBuilderInterface
    {
        return new CreateVideoBuilder();
    }
    
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

            if ($input->videoFile != null) {
                $this->repository->updateMedia($this->builder->getEntity());
            }

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