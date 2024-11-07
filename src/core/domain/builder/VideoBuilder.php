<?php

namespace core\domain\builder;

use core\domain\entity\Video;
use core\domain\enum\MediaStatus;
use core\domain\valueobject\Media;

class VideoBuilder implements VideoBuilderInterface
{
    private ?Video $video = null;
    public function __construct() {
        $this->video = null;
    }

    public function createEntity(object $input): void
    {
        $this->video = new Video(
            titulo: $input->titulo,
            descricao: $input->descricao,
            dtFilmagem: $input->dtFilmagem,
        );
    }

    public function addCategorias(object $input): void
    {
        foreach ($input->categoriasIds as $categoriaId) {
            $this->video->vincularCategoria($categoriaId);
        }
    }

    public function addAtletas(object $input): void
    {
        foreach ($input->atletasIds as $atletaId) {
            $this->video->vincularAtleta($atletaId);
        }
    }
    
    public function addVideoMedia(string $path, MediaStatus $mediaStatus): void
    {
        $media = new Media($path, $mediaStatus);
        $this->video->setVideoFile($media);
    }

    public function getEntity(): Video
    {
        return $this->video;
    }
}