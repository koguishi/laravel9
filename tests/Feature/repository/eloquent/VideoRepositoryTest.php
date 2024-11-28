<?php

namespace Tests\Feature\repository\eloquent;

use App\Models\ {
    Atleta as AtletaModel,
    Categoria as CategoriaModel,
};
use App\Models\Video as VideoModel;
use app\repository\eloquent\VideoRepository;
use core\domain\entity\Video;
use core\domain\exception\NotFoundException;
use core\domain\repository\PaginationInterface;
use core\domain\repository\VideoRepositoryInterface;
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

        $responseA = $this->repository->read($videoA->id);
        $responseA->alterar(
            titulo: $responseA->titulo . 'ALTERADO',
            dtFilmagem: VideoModel::factory()->valid_dtFilmagem(),
        );
        $this->repository->update($responseA);
        $this->assertDatabaseHas('videos', [
            'titulo' => $responseA->titulo,
            'dt_filmagem' => $responseA->dtFilmagem,
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

    public function testPaginate()
    {
        VideoModel::factory(count: 20)->create();

        $response = $this->repository->paginate();

        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(15, $response->items());
    }

}
