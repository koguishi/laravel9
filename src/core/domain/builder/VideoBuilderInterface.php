<?php

namespace core\domain\builder;

use core\domain\entity\Video;
use core\domain\enum\MediaStatus;

interface VideoBuilderInterface
{
    public function createEntity(object $input): VideoBuilderInterface;
    public function addCategoriasIds(object $input): VideoBuilderInterface;
    public function addAtletasIds(object $input): VideoBuilderInterface;
    public function addVideoMedia(string $path, MediaStatus $mediaStatus): VideoBuilderInterface;
    public function getEntity(): Video;
}