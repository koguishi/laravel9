<?php

namespace app\repository\eloquent;

use app\Models\Video as Model;
use app\repository\PaginationPresenter;
use core\domain\entity\Entity;
use core\domain\entity\Video;
use core\domain\exception\NotFoundException;
use core\domain\repository\PaginationInterface;
use core\domain\repository\VideoRepositoryInterface;
use core\domain\valueobject\Uuid;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;

class VideoRepository implements VideoRepositoryInterface
{
    protected $model;
    
    public function __construct(Model $model) {
        $this->model = $model;
    }

    private function toEntity(object $object): Video {
        $entity = new Video(
            id: new Uuid($object->id),
            titulo: $object->titulo,
            descricao: $object->descricao,
            dtFilmagem: new DateTime($object->dt_filmagem),
        );

        return $entity;
    }     
    public function create(Entity $entity): Video
    {
        $videoDb = $this->model->create([
            'id' => $entity->id(),
            'titulo' => $entity->titulo,
            'descricao' => $entity->descricao,
            'dt_filmagem' => $entity->dtFilmagem->format('Y-m-d'),
        ]);

        return $this->toEntity($videoDb);
    }
    public function read(string $id): Video
    {
        $videoDb = $this->model->find($id);
        if (! $videoDb) {
            throw new NotFoundException('Video not found');
        }

        return $this->toEntity($videoDb);
    }
    public function update(Entity $entity): Video
    {
        $videoDb = $this->model->find($entity->id());
        if (! $videoDb) {
            throw new NotFoundException('Video not found');
        }

        $videoDb->update([
            'titulo' => $entity->titulo,
            'descricao' => $entity->descricao,
            'dt_filmagem' => $entity->dtFilmagem,
        ]);

        $videoDb->refresh();

        return $this->toEntity($videoDb);
    }
    public function delete(string $id): bool
    {
        $videoDb = $this->model->find($id);
        if (! $videoDb) {
            throw new NotFoundException('Video not found');
        }        
        return $videoDb->delete();        
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
        $query = $this->model;

        if ($filter) {
            $query = $query->where('titulo', 'LIKE', "%{$filter}%")
                ->orWhere('descricao', 'LIKE', "%{$filter}%");
        }

        if ($filter_dtFilmagem_inicial) {
            $query = $query->whereDate('dt_filmagem', '>=', $filter_dtFilmagem_inicial->format('Y-m-d'));
        }

        if ($filter_dtFilmagem_final) {
            $query = $query->whereDate('dt_filmagem', '<=', $filter_dtFilmagem_final->format('Y-m-d'));
        }

        if (!empty($order)) {
            $arrOrder = json_decode($order);
            foreach ($arrOrder as $column => $direction) {
                $query = $query->orderBy($column, $direction);
            }
        }

        $paginator = $query->paginate(
            page: $page,
            perPage: $perPage,
        );

        return new PaginationPresenter($paginator);
    }

    public function updateMedia(Video $video): Video
    {
        return $video;
    }
}