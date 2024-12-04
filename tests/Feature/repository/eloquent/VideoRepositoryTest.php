<?php

namespace Tests\Feature\repository\eloquent;

use App\Models\ {
    Atleta as AtletaModel,
    Categoria as CategoriaModel,
};
use App\Models\Video as VideoModel;
use app\repository\eloquent\VideoRepository;
use core\domain\entity\Video;
use core\domain\enum\MediaStatus;
use core\domain\exception\NotFoundException;
use core\domain\repository\PaginationInterface;
use core\domain\repository\VideoRepositoryInterface;
use core\domain\valueobject\Media;
use DateTime;
use Tests\TestCase;
use Throwable;

class VideoRepositoryTest extends TestCase
{
    /**
     * @var VideoRepository
     */
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new VideoRepository(new VideoModel());
    }    

    public function testImplementsInterface()
    {
        $this->assertInstanceOf(
            VideoRepositoryInterface::class,
            $this->repository
        );
    }

    public function testCreate()
    {
        $entity = New Video(
            titulo: 'ÁÉÊ Çção',
            descricao: 'filme de ação',
            dtFilmagem: new DateTime('2001-01-01'),
        );
        
        $response = $this->repository->create($entity);

        $this->assertInstanceOf(Video::class, $response);
        $this->assertDatabaseHas('videos', [
            'titulo' => $entity->titulo,
            'dt_filmagem' => $entity->dtFilmagem(),
            'created_at' => $entity->criadoEm(),
        ]);

        $this->assertEquals($entity->titulo, $response->titulo);
        $this->assertEquals($entity->descricao, $response->descricao);
        $this->assertEquals($entity->dtFilmagem(), $response->dtFilmagem());
    }

    public function testCreateWithRelationships()
    {
        $categorias = CategoriaModel::factory(count: 4)->create();
        $atletas = AtletaModel::factory(count: 4)->create();

        $entity = New Video(
            titulo: 'ÁÉÊ Çção',
            descricao: 'filme de ação',
            dtFilmagem: new DateTime('2001-01-01'),
        );

        foreach ($categorias as $key => $categoria) {
            $entity->vincularCategoria($categoria->id);
        }
        foreach ($atletas as $key => $atleta) {
            $entity->vincularAtleta($atleta->id);
        }

        $response = $this->repository->create($entity);

        $this->assertInstanceOf(Video::class, $response);
        $this->assertDatabaseHas('videos', [
            'titulo' => $entity->titulo,
            'dt_filmagem' => $entity->dtFilmagem(),
            'created_at' => $entity->criadoEm(),
        ]);
        $this->assertCount(4, $response->categoriaIds);
        $this->assertCount(4, $response->atletaIds);

        $orderedCategoriaIds = $response->categoriaIds;
        sort($orderedCategoriaIds);
        $orderedAltetaIds = $response->atletaIds;
        sort($orderedAltetaIds);

        $this->assertEquals(
            $categorias->sortBy('id')->pluck('id')->toArray(),
            $orderedCategoriaIds
        );
        $this->assertEquals(
            $atletas->sortBy('id')->pluck('id')->toArray(),
            $orderedAltetaIds
        );
    }

    public function testRead()
    {
        $videoA = VideoModel::factory()->create();
        $videoB = VideoModel::factory()->create();

        $responseA = $this->repository->read($videoA->id);
        $this->assertInstanceOf(Video::class, $responseA);
        $this->assertEquals($videoA->id, $responseA->id);
        $this->assertEquals($videoA->titulo, $responseA->titulo);
        $this->assertEquals($videoA->dt_filmagem, $responseA->dtFilmagem);

        $responseB = $this->repository->read($videoB->id);
        $this->assertInstanceOf(Video::class, $responseB);
        $this->assertEquals($videoB->id, $responseB->id);
        $this->assertEquals($videoB->titulo, $responseB->titulo);
        $this->assertEquals($videoB->dt_filmagem, $responseB->dtFilmagem);
    }

    public function testReadNotFound()
    {
        try {
            $this->repository->read('fakeValue');

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }

    public function testUpdate()
    {
        $videoA = VideoModel::factory()->create();
        $this->assertDatabaseHas('videos', [
            'titulo' => $videoA->titulo,
            'dt_filmagem' => $videoA->dt_filmagem
        ]);

        $entity = $this->repository->read($videoA->id);
        $entity->alterar(
            titulo: $entity->titulo . 'ALTERADO',
            dtFilmagem: VideoModel::factory()->valid_dtFilmagem(),
        );
        $updated = $this->repository->update($entity);

        $this->assertEquals($entity->titulo, $updated->titulo);
        $this->assertEquals($entity->descricao, $updated->descricao);
        $this->assertEquals($entity->dtFilmagem(), $updated->dtFilmagem());

        $this->assertDatabaseHas('videos', [
            'titulo' => $entity->titulo,
            'dt_filmagem' => $entity->dtFilmagem,
        ]);

        $this->assertDatabaseMissing('videos', [
            'titulo' => $videoA->titulo,
        ]);
        $this->assertDatabaseMissing('videos', [
            'dt_filmagem' => $videoA->dt_filmagem,
        ]);
    }
    public function testUpdateNotFound()
    {
        $entity = New Video(
            titulo: 'titulo qualquer',
            descricao: 'descricao qq',
            dtFilmagem: VideoModel::factory()->valid_dtFilmagem(),
        );
        try {
            $this->repository->update($entity);

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }

    public function testUpdateWithRelationships()
    {
        $categorias = CategoriaModel::factory(count: 5)->create();
        $atletas = AtletaModel::factory(count: 5)->create();

        $entity = New Video(
            titulo: 'ÁÉÊ Çção',
            descricao: 'filme de ação',
            dtFilmagem: new DateTime('2001-01-01'),
        );

        $created = $this->repository->create($entity);

        $this->assertDatabaseHas('videos', [
            'titulo' => $entity->titulo,
            'dt_filmagem' => $entity->dtFilmagem(),
            'created_at' => $entity->criadoEm(),
        ]);
        $this->assertCount(0, $created->categoriaIds);
        $this->assertCount(0, $created->atletaIds);
        $this->assertDatabaseCount('video_categoria', 0);
        $this->assertDatabaseCount('video_atleta', 0);

        foreach ($categorias as $key => $categoria) {
            $entity->vincularCategoria($categoria->id);
        }
        foreach ($atletas as $key => $atleta) {
            $entity->vincularAtleta($atleta->id);
        }

        $updated = $this->repository->update($entity);

        $this->assertCount(5, $updated->categoriaIds);
        $this->assertCount(5, $updated->atletaIds);
        $this->assertDatabaseCount('video_categoria', 5);
        $this->assertDatabaseCount('video_atleta', 5);

        $orderedCategoriaIds = $updated->categoriaIds;
        sort($orderedCategoriaIds);
        $orderedAltetaIds = $updated->atletaIds;
        sort($orderedAltetaIds);

        $this->assertEquals(
            $categorias->sortBy('id')->pluck('id')->toArray(),
            $orderedCategoriaIds
        );
        $this->assertEquals(
            $atletas->sortBy('id')->pluck('id')->toArray(),
            $orderedAltetaIds
        );
    }    

    public function testDelete()
    {
        $videoA = VideoModel::factory()->create();
        $this->assertDatabaseHas('videos', [
            'titulo' => $videoA->titulo,
            'dt_filmagem' => $videoA->dt_filmagem
        ]);

        $this->repository->delete($videoA->id);
        $this->assertSoftDeleted($videoA);
    }

    public function testDeleteNotFound()
    {
        try {
            $this->repository->delete('fakeValue');

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }

    public function testUpdateMedia()
    {
        $videoModel = VideoModel::factory()->create();
        $this->assertDatabaseHas('videos', [
            'titulo' => $videoModel->titulo,
            'dt_filmagem' => $videoModel->dt_filmagem,
        ]);
        $this->assertDatabaseMissing('video_medias', [
            'video_id' => $videoModel->id,
        ]);

        $video = $this->repository->read($videoModel->id);
        $this->assertNull($video->videoFile());

        $media = new Media(
            filePath: 'caminhoDoArquivo',
            mediaStatus: MediaStatus::PENDING,
        );
        $video->setVideoFile($media);
        // dd($video);
        $videoDb = $this->repository->updateMedia($video);
        dd('ponto final.');

        $this->assertDatabaseCount('video_medias', 1);
        $this->assertDatabaseHas('video_medias', [
            'video_id' => $videoModel->id,
            'file_path' => 'caminhoDoArquivo',
            'media_status' => MediaStatus::PENDING,

        ]);

        $media = new Media(
            filePath: 'outroCaminhoDoArquivo',
            mediaStatus: MediaStatus::PROCESSING,
        );
        $video->setVideoFile($media);
        $videoDb = $this->repository->updateMedia($video);
        $this->assertDatabaseCount('video_medias', 1);
        $this->assertDatabaseHas('video_medias', [
            'file_path' => 'outroCaminhoDoArquivo',
            'media_status' => MediaStatus::PROCESSING,
        ]);
        // dump($videoModel->titulo);
        // dd($videoDb);
        // $this->assertNotNull($videoDb->videoFile());

        $media = new Media(
            filePath: 'outroCaminhoDoArquivo',
            mediaStatus: MediaStatus::COMPLETE,
        );
        $video->setVideoFile($media);
        $this->repository->updateMedia($video);
        $this->assertDatabaseCount('video_medias', 1);
        $this->assertDatabaseHas('video_medias', [
            'file_path' => 'outroCaminhoDoArquivo',
            'media_status' => MediaStatus::COMPLETE,
        ]);
    }

    /**
     * @dataProvider DataProviderPaginate
     */
    public function testPaginate(
        int $page,
        int $perPage,
        int $totalItems,
    )
    {
        $lastPage = intdiv($totalItems, $perPage);
        $lastPage += ($totalItems % $perPage) > 0 ? 1 : 0;
        $itemsOnLastPage = $totalItems % $perPage;
        $itemsOnLastPage = $itemsOnLastPage == 0 ? $perPage : $itemsOnLastPage;
        $itemsCount = $page == $lastPage ? $itemsOnLastPage : $perPage;

        VideoModel::factory(count: $totalItems)->create();

        $response = $this->repository->paginate(
            page: $page,
            perPage: $perPage,
        );
        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount($itemsCount, $response->items());
        $this->assertEquals($totalItems, $response->total());
        $this->assertEquals($page, $response->currentPage());
        $this->assertEquals($lastPage, $response->lastPage());
        $this->assertEquals($perPage, $response->perPage());
    }

    public function dataProviderPaginate()
    {
        return [
            ['page' => 1, 'perPage' => 7, 'totalItems' => 7],
            ['page' => 1, 'perPage' => 6, 'totalItems' => 7],
            ['page' => 1, 'perPage' => 5, 'totalItems' => 7],
            ['page' => 1, 'perPage' => 4, 'totalItems' => 7],
            ['page' => 1, 'perPage' => 3, 'totalItems' => 7],
            ['page' => 1, 'perPage' => 2, 'totalItems' => 7],
            ['page' => 1, 'perPage' => 1, 'totalItems' => 7],

            ['page' => 2, 'perPage' => 6, 'totalItems' => 7],
            ['page' => 2, 'perPage' => 5, 'totalItems' => 7],
            ['page' => 2, 'perPage' => 4, 'totalItems' => 7],
            ['page' => 2, 'perPage' => 3, 'totalItems' => 7],
            ['page' => 2, 'perPage' => 2, 'totalItems' => 7],
            ['page' => 2, 'perPage' => 1, 'totalItems' => 7],

            ['page' => 3, 'perPage' => 3, 'totalItems' => 7],
            ['page' => 3, 'perPage' => 2, 'totalItems' => 7],
            ['page' => 3, 'perPage' => 1, 'totalItems' => 7],

            ['page' => 4, 'perPage' => 2, 'totalItems' => 7],
            ['page' => 4, 'perPage' => 1, 'totalItems' => 7],

            ['page' => 5, 'perPage' => 1, 'totalItems' => 7],
            ['page' => 6, 'perPage' => 1, 'totalItems' => 7],
            ['page' => 7, 'perPage' => 1, 'totalItems' => 7],
        ];
    }

    /**
     * @dataProvider dataProviderPaginatePageWithoutItems
     */
    public function testPaginatePageWithoutItems(
        int $page,
        int $perPage,
        int $totalItems,
    )
    {
        $lastPage = intdiv($totalItems, $perPage);
        $lastPage += ($totalItems % $perPage) > 0 ? 1 : 0;

        VideoModel::factory(count: $totalItems)->create();

        $response = $this->repository->paginate(
            page: $page,
            perPage: $perPage,
        );
        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(0, $response->items());
        $this->assertEquals($totalItems, $response->total());
        $this->assertEquals($page, $response->currentPage());
        $this->assertEquals($lastPage, $response->lastPage());
        $this->assertEquals($perPage, $response->perPage());
    }

    public function dataProviderPaginatePageWithoutItems()
    {
        return [
            ['page' => 2, 'perPage' => 7, 'totalItems' => 7],
            ['page' => 3, 'perPage' => 6, 'totalItems' => 7],
            ['page' => 8, 'perPage' => 1, 'totalItems' => 7],
        ];
    }
}
