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
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreate()
    {
        $repository = new CategoriaRepository(new CategoriaModel());

        $entity = New CategoriaEntity(
            nome: 'ÁÉÊ Çção',
            descricao: 'é nóis ...',
            ativo: true,
        );
        
        $response = $repository->create($entity);

        $this->assertInstanceOf(CategoriaRepositoryInterface::class, $repository);
        $this->assertInstanceOf(CategoriaEntity::class, $response);
        $this->assertDatabaseHas('categorias', [
            'nome' => $entity->nome,
            'descricao' => $entity->descricao,
            'ativo' => $entity->ativo,
            'created_at' => $entity->criadoEm(),
        ]);
    }
}
