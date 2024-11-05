<?php

namespace Tests\Feature\repository\eloquent;

use App\Models\Categoria as CategoriaModel;
use app\repository\eloquent\CategoriaRepository;
use core\domain\entity\Categoria as CategoriaEntity;
use core\domain\exception\NotFoundException;
use core\domain\repository\CategoriaRepositoryInterface;
use core\domain\repository\PaginationInterface;
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
        ]);
    }

    public function testRead()
    {
        $categoriaA = CategoriaModel::factory()->create();
        $categoriaB = CategoriaModel::factory()->create();

        $responseA = $this->repository->read($categoriaA->id);
        $this->assertInstanceOf(CategoriaEntity::class, $responseA);
        $this->assertEquals($categoriaA->id, $responseA->id);
        $this->assertEquals($categoriaA->nome, $responseA->nome);
        $this->assertEquals($categoriaA->descricao, $responseA->descricao);
        $this->assertEquals($categoriaA->ativo, $responseA->ativo);

        $responseB = $this->repository->read($categoriaB->id);
        $this->assertInstanceOf(CategoriaEntity::class, $responseB);
        $this->assertEquals($categoriaB->id, $responseB->id);
        $this->assertEquals($categoriaB->nome, $responseB->nome);
        $this->assertEquals($categoriaB->descricao, $responseB->descricao);
        $this->assertEquals($categoriaB->ativo, $responseB->ativo);
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
        $this->assertSoftDeleted($categoriaA);
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

    public function testGetIds()
    {
        $categorias = CategoriaModel::factory()->count(20)->create();
        $response = $this->repository->getIds([$categorias[0]->id]);
        $this->assertCount(1, $response);
        $this->assertEquals($categorias[0]->id, $response[0]);

        $response = $this->repository->getIds([
            $categorias[1]->id,
            $categorias[2]->id,
        ]);
        $this->assertCount(2, $response);
        $this->assertContains($categorias[1]->id, $response);
        $this->assertContains($categorias[2]->id, $response);
    }

    public function testList()
    {
        $categorias = CategoriaModel::factory()->count(20)->create();
        $response = $this->repository->list();
        $this->assertEquals(count($categorias), count($response));
        // TODO: checar o conteudo de $categorias e $response
    }

    public function testListOrderByNomeAsc()
    {
        $categoriasModel = CategoriaModel::factory()->count(20)->create();
        $arrCategorias = $this->repository->list(
            order: '{"nome": "asc"}',
        );
        $this->assertEquals(count($categoriasModel), count($arrCategorias));

        $arrNomes = [];
        foreach ($categoriasModel as $key => $categoriaModel) {
            array_push($arrNomes, $categoriaModel->nome);
        }
        sort($arrNomes);

        foreach ($arrCategorias as $key => $arrCategoria) {
            $this->assertEquals($arrNomes[$key], $arrCategorias[$key]['nome']);
        }
    }

    public function testListFilterByNome()
    {
        $categoriasModel = CategoriaModel::factory()->count(50)->create();

        $arrCategorias = $this->repository->list(
            filter: $categoriasModel[0]->nome
        );
        $this->assertGreaterThanOrEqual(1, count($arrCategorias));
        foreach ($arrCategorias as $key => $arrCategoria) {
            $this->assertEquals($categoriasModel[0]->nome, $arrCategorias[$key]['nome']);
        }

        $arrCategorias = $this->repository->list(
            filter: $categoriasModel[count($categoriasModel)-1]->nome
        );
        $this->assertGreaterThanOrEqual(1, count($arrCategorias));
        foreach ($arrCategorias as $key => $arrCategoria) {
            $this->assertEquals(
                $categoriasModel[count($categoriasModel)-1]->nome,
                $arrCategorias[$key]['nome'],
            );
        }
    }

    public function testListFilterByDescricao()
    {
        $categoriasModel = CategoriaModel::factory()->count(50)->create();

        $arrCategorias = $this->repository->list(
            filter: $categoriasModel[0]->descricao
        );
        $this->assertGreaterThanOrEqual(1, count($arrCategorias));
        foreach ($arrCategorias as $key => $arrCategoria) {
            $this->assertEquals(
                $categoriasModel[0]->descricao,
                $arrCategorias[$key]['descricao'],
            );
        }

        $arrCategorias = $this->repository->list(
            filter: $categoriasModel[count($categoriasModel)-1]->descricao
        );
        $this->assertGreaterThanOrEqual(1, count($arrCategorias));
        foreach ($arrCategorias as $key => $arrCategoria) {
            $this->assertEquals(
                $categoriasModel[count($categoriasModel)-1]->descricao,
                $arrCategorias[$key]['descricao'],
            );
        }
    }

    public function testListFilterNotFound()
    {
        $categoriasModel = CategoriaModel::factory()->count(50)->create();
        $arrCategorias = $this->repository->list(
            filter: 'xyz'
        );
        $this->assertEquals(0, count($arrCategorias));
    }

    public function testPaginate()
    {
        CategoriaModel::factory()->count(20)->create();

        $response = $this->repository->paginate();

        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(15, $response->items());
    }

    public function testPaginateWithFilter()
    {
        CategoriaModel::factory()->count(20)->create();
        $categoria = CategoriaModel::factory()->create();

        $response = $this->repository->paginate(
            filter: $categoria->nome
        );

        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(1, $response->items());
        $this->assertEquals($categoria->nome, $response->items()[0]->nome);
    }

    public function testPaginateEmpty()
    {
        $response = $this->repository->paginate();

        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(0, $response->items());
    }

    public function testPaginateOrderByNomeAsc()
    {
        $categoriasModel = CategoriaModel::factory()->count(20)->create();
        $arrCategorias = $this->repository->paginate(
            order: '{"nome": "asc"}',
        );

        $arrNomes = [];
        foreach ($categoriasModel as $key => $categoriaModel) {
            array_push($arrNomes, $categoriaModel->nome);
        }
        sort($arrNomes);

        foreach ($arrCategorias->items() as $key => $categoria) {
            $this->assertEquals($arrNomes[$key], $categoria->nome);
        }
    }
}
