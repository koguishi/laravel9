<?php

namespace Tests\Feature\repository\eloquent;

use App\Models\Categoria as CategoriaModel;
use app\repository\eloquent\CategoriaRepository;
use core\domain\entity\Categoria as CategoriaEntity;
use core\domain\exception\NotFoundException;
use core\domain\repository\CategoriaRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Throwable;

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
        $categoriaA = CategoriaModel::factory()->create();
        $categoriaB = CategoriaModel::factory()->create();

        $responseA = $this->repository->read($categoriaA->id);
        $this->assertInstanceOf(CategoriaEntity::class, $responseA);
        $this->assertDatabaseHas('categorias', [
            'nome' => $categoriaA->nome,
            'descricao' => $categoriaA->descricao,
            'ativo' => $categoriaA->ativo,
            'created_at' => $categoriaA->created_at,
        ]);

        $responseB = $this->repository->read($categoriaB->id);
        $this->assertInstanceOf(CategoriaEntity::class, $responseB);
        $this->assertDatabaseHas('categorias', [
            'nome' => $categoriaB->nome,
            'descricao' => $categoriaB->descricao,
            'ativo' => $categoriaB->ativo,
            'created_at' => $categoriaB->created_at,
        ]);
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
        $categoriaA = CategoriaModel::factory()->create();
        $this->assertDatabaseHas('categorias', [
            'nome' => $categoriaA->nome,
            'descricao' => $categoriaA->descricao,
            'ativo' => $categoriaA->ativo,
            'created_at' => $categoriaA->created_at,
        ]);

        $responseA = $this->repository->read($categoriaA->id);
        $responseA->alterar(
            $responseA->nome . 'ALTERADO',
            $responseA->descricao . 'ALTERADO',
        );
        $this->repository->update($responseA);
        $this->assertDatabaseHas('categorias', [
            'nome' => $responseA->nome,
            'descricao' => $responseA->descricao,
            'ativo' => $responseA->ativo,
        ]);

        $this->assertDatabaseMissing('categorias', [
            'nome' => $categoriaA->nome,
        ]);
        $this->assertDatabaseMissing('categorias', [
            'descricao' => $categoriaA->descricao,
        ]);
    }

    public function testUpdateNotFound()
    {
        $entity = New CategoriaEntity(
            nome: 'uma categoria qq ...',
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
        $categoriaA = CategoriaModel::factory()->create();
        $this->assertDatabaseHas('categorias', [
            'nome' => $categoriaA->nome,
            'descricao' => $categoriaA->descricao,
            'ativo' => $categoriaA->ativo,
            'created_at' => $categoriaA->created_at,
        ]);

        $this->repository->delete($categoriaA->id);
        $this->assertDatabaseMissing('categorias', [
            'nome' => $categoriaA->nome,
        ]);
        $this->assertDatabaseMissing('categorias', [
            'descricao' => $categoriaA->descricao,
        ]);
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

}
