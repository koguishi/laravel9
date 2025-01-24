<?php

namespace core\domain\builder;

use core\domain\entity\Video;
use core\domain\valueobject\Uuid;
use DateTime;

class UpdateVideoBuilder extends CreateVideoBuilder
{
    public function createEntity(object $input): VideoBuilderInterface
    {
        $this->video = new Video(
            id: new Uuid($input->id),
            titulo: $input->titulo,
            descricao: $input->descricao,
            dtFilmagem: $input->dtFilmagem,
            criadoEm: new DateTime($input->criadoEm),
        );
        return $this;
    }

    public function setEntity(Video $video)
    {
        $this->video = $video;

        return $this;
    }

    public function addCategoriasIds(object $input): VideoBuilderInterface
    {
        $this->video->desvincularCategorias();
        foreach ($input->categoriasIds as $categoriaId) {
            $this->video->vincularCategoria($categoriaId);
        }
        return $this;
    }

    public function addAtletasIds(object $input): VideoBuilderInterface
    {
        $this->video->desvincularAtletas();
        foreach ($input->atletasIds as $atletaId) {
            $this->video->vincularAtleta($atletaId);
        }
        return $this;
    }

}