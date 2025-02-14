<?php

namespace app\repository\eloquent;

use App\Models\Video as VideoModel;
use app\repository\PaginationPresenter;
use core\domain\entity\Entity;
use core\domain\entity\Video;
use core\domain\exception\NotFoundException;
use core\domain\repository\PaginationInterface;
use core\domain\repository\VideoRepositoryInterface;
use core\domain\valueobject\Media;
use core\domain\valueobject\Uuid;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;

class VideoRepository implements VideoRepositoryInterface
{
    protected $model;
    
    public function __construct(VideoModel $model) {
        $this->model = $model;
    }

    public function create(Entity $entity): Video
    {
        $videoDb = $this->model->create([
            'id' => $entity->id(),
            'titulo' => $entity->titulo,
            'descricao' => $entity->descricao,
            'dt_filmagem' => $entity->dtFilmagem->format('Y-m-d'),
        ]);

        $this->syncRelationships($videoDb, $entity);

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

        $this->syncRelationships($videoDb, $entity);

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
        $videoDb = $this->model->find($video->id());
        if (! $videoDb) {
            throw new NotFoundException('Video not found');
        }

        // Opção para o método updateOrCreate do Laravel
        // if ($media = $video->videoFile()) {
        //     $action = $videoDb->media()->first() ? 'update' : 'create';
        //     $videoDb->media()->{$action}([
        //         'video_id' => $video->id(),
        //         'file_path' => $media->filePath,
        //         'encoded_path' => $media->encodedPath,
        //         'media_status' => $media->mediaStatus,
        //     ]);
        //     $videoDb->refresh();
        // }
        // método updateOrCreate é do Laravel
        $media = $videoDb->media()->updateOrCreate(
            [ 'video_id' => $video->id() ],
            [
                'file_path' => $video->videoFile()->filePath,
                'encoded_path' => $video->videoFile()->encodedPath,
                'media_status' => $video->videoFile()->mediaStatus,
            ],
        );

        return $this->toEntity($videoDb);
    }

    private function toEntity(object $modelVideo): Video {
        $entity = new Video(
            id: new Uuid($modelVideo->id),
            titulo: $modelVideo->titulo,
            descricao: $modelVideo->descricao,
            dtFilmagem: new DateTime($modelVideo->dt_filmagem),
        );

        foreach ($modelVideo->categorias as $key => $categoria) {
            $entity->vincularCategoria($categoria->id);
        }
        foreach ($modelVideo->atletas as $key => $atleta) {
            $entity->vincularAtleta($atleta->id);
        }

        if ($media = $modelVideo->media()->first())
        {
            $entity->setVideoFile(
                videoFile: new Media(
                    filePath: $media->file_path,
                    mediaStatus: $media->media_status,
                    encodedPath: $media->encoded_path,
                )
            );
        }

        return $entity;
    } 

    private function syncRelationships(VideoModel $model, Video $video)
    {
        $model->categorias()->sync($video->categoriaIds);
        $model->atletas()->sync($video->atletaIds);
    }

}