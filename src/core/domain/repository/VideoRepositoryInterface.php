<?php

namespace core\domain\repository;

use core\domain\entity\Video;
use DateTime;

interface VideoRepositoryInterface extends EntityRepositoryInterface
{
    // public function list(
    //     string $order = '',
    //     string $filter_titulo = '',
    //     ?DateTime $filter_dtFilmagem_inicial = null,
    //     ?DateTime $filter_dtFilmagem_final = null,
    // ): array;

    public function paginate(
        int $page = 1,
        int $perPage = 15,
        ?string $order = '',
        ?string $filter = '',
        ?DateTime $filter_dtFilmagem_inicial = null,
        ?DateTime $filter_dtFilmagem_final = null,
    ): PaginationInterface;

    public function updateMedia(Video $video): Video;
}
