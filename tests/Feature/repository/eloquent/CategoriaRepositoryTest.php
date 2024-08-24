<?php

namespace Tests\Feature\repository\eloquent;

use App\Models\Categoria as CategoriaModel;
use app\repository\eloquent\CategoriaRepository;
use core\domain\entity\Categoria as CategoriaEntity;
use core\domain\repository\CategoriaRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoriaRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new CategoriaRepository(new CategoriaModel());
    }    

    public function testCreate()
    {
        $entity = New CategoriaEntity(
            nome: 'ÁÉÊ Çção',
            descricao: 'é nóis ...',
            ativo: true,
        );
        
        $response = $this->repository->create($entity);

        $this->assertInstanceOf(CategoriaRepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(CategoriaEntity::class, $response);
        $this->assertDatabaseHas('categorias', [
            'nome' => $entity->nome,
            'descricao' => $entity->descricao,
            'ativo' => $entity->ativo,
            'created_at' => $entity->criadoEm(),
        ]);
    }

    public function testRead()
    {
        $categoria1 = New CategoriaEntity(
            nome: 'Treinos',
            descricao: 'Vídeo de treinos',
            ativo: true,
        );
        $this->repository->create($categoria1);
        $categoria2 = New CategoriaEntity(
            nome: 'Competições',
            descricao: 'Vídeo de competições',
            ativo: true,
        );
        $this->repository->create($categoria2);

        $this->assertDatabaseHas('categorias', [
            'nome' => $categoria1->nome,
            'descricao' => $categoria1->descricao,
            'ativo' => $categoria1->ativo,
            'created_at' => $categoria1->criadoEm(),
        ]);
        $this->assertDatabaseHas('categorias', [
            'nome' => $categoria2->nome,
            'descricao' => $categoria2->descricao,
            'ativo' => $categoria2->ativo,
            'created_at' => $categoria2->criadoEm(),
        ]);
        $response = $this->repository->read($categoria1->id);

        $this->assertInstanceOf(CategoriaEntity::class, $response);
        $this->assertDatabaseHas('categorias', [
            'nome' => $categoria1->nome,
            'descricao' => $categoria1->descricao,
            'ativo' => $categoria1->ativo,
            'created_at' => $categoria1->criadoEm(),
        ]);
    }

}
