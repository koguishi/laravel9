<?php

namespace core\domain\builder;

use core\domain\entity\Video;
use core\domain\enum\MediaStatus;

interface VideoBuilderInterface
{
    public function createEntity(object $input): void;
    public function addCategorias(object $input): void;
    public function addAtletas(object $input): void;
    public function addVideoMedia(string $path, MediaStatus $mediaStatus): void;
    public function getEntity(): Video;
}