<?php

namespace app\repository\eloquent;

use app\Models\Video as Model;
use app\repository\PaginationPresenter;
use core\domain\entity\Entity;
use core\domain\entity\Video;
use core\domain\repository\PaginationInterface;
use core\domain\repository\VideoRepositoryInterface;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;

class VideoRepository implements VideoRepositoryInterface
{
    protected $model;
    
    public function __construct(Model $model) {
        $this->model = $model;
    }
    public function create(Entity $entity): Entity
    {
        return $entity;
    }
    public function read(string $id): Entity
    {
        $video = new Video(
            titulo: '',
            descricao: '',
            dtFilmagem: new DateTime(),
        );
        return $video;
        
    }
    public function update(Entity $entity): Entity
    {
        return $entity;
    }
    public function delete(string $id): bool
    {
        return true;
    }

    public function paginate(
        int $page = 1,
        int $perPage = 15,
        ?string $order = '',
        ?string $filter = '',
        ?DateTime $filter_dtFilmagem_inicial = null,
        ?DateTime $filter_dtFilmagem_final = null,
    ): PaginationInterface
    {
        return new PaginationPresenter(
            new LengthAwarePaginator(
                items: [],
                total: 1,
                perPage: 1,
            )
        );
    }

    public function updateMedia(Video $video): Video
    {
        return $video;
    }
}