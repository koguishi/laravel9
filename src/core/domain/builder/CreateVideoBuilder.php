<?php

namespace core\domain\builder;

use core\domain\entity\Video;
use core\domain\enum\MediaStatus;
use core\domain\valueobject\Media;

class CreateVideoBuilder implements VideoBuilderInterface
{
    protected ?Video $video = null;
    public function __construct() {
        $this->video = null;
    }

    public function createEntity(object $input): VideoBuilderInterface
    {
        $this->video = new Video(
            titulo: $input->titulo,
            descricao: $input->descricao,
            dtFilmagem: $input->dtFilmagem,
        );
        return $this;
    }

    public function addCategoriasIds(object $input): VideoBuilderInterface
    {
        foreach ($input->categoriasIds as $categoriaId) {
            $this->video->vincularCategoria($categoriaId);
        }
        return $this;
    }

    public function addAtletasIds(object $input): VideoBuilderInterface
    {
        foreach ($input->atletasIds as $atletaId) {
            $this->video->vincularAtleta($atletaId);
        }
        return $this;
    }
    
    public function addVideoMedia(string $path, MediaStatus $mediaStatus): VideoBuilderInterface
    {
        $media = new Media($path, $mediaStatus);
        $this->video->setVideoFile($media);
        return $this;
    }

    public function getEntity(): Video
    {
        return $this->video;
    }
}