<?php

namespace core\usecase\video;

use core\domain\builder\UpdateVideoBuilder;
use core\domain\builder\VideoBuilderInterface;
use core\domain\entity\Video;

class UpdateVideoUsecase extends BaseVideoUsecase
{
    protected function getBuilder(): VideoBuilderInterface
    {
        return new UpdateVideoBuilder();
    }    
    public function execute(UpdateVideoInput $input): UpdateVideoOutput
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

        /**
         * @var Video
         */
        $video = $this->repository->read($input->id);
        $video->alterar(
            titulo: $input->titulo,
            descricao: $input->descricao,
        );

        /**
         * @var UpdateVideoBuilder
         */
        $builder = $this->builder;
        $builder->setEntity($video)
            ->addCategoriasIds($input)
            ->addAtletasIds($input);

        try {
            // persitir a entity do video usando $repository
            $this->repository->update($this->builder->getEntity());

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

    private function createOutput(Video $video): UpdateVideoOutput
    {
        return new UpdateVideoOutput(
            id: $video->id(),
            titulo: $video->titulo,
            descricao: $video->descricao,
            dtFilmagem: $video->dtFilmagem,
            pathVideoFile: $video->videoFile()?->filePath,
            categorias: $video->categoriaIds,
            atletas: $video->atletaIds,
        );
    }
}