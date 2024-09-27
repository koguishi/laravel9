<?php

namespace Tests\Feature\repository\eloquent;

use App\Models\Atleta as AtletaModel;
use app\repository\eloquent\AtletaRepository;
use core\domain\entity\Atleta;
use core\domain\exception\NotFoundException;
use core\domain\repository\AtletaRepositoryInterface;
use DateTime;
use Tests\TestCase;
use Throwable;

class AtletaRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new AtletaRepository(new AtletaModel());
    }    

    public function testImplementsInterface()
    {
        $this->assertInstanceOf(AtletaRepositoryInterface::class, $this->repository);
    }

    public function testCreate()
    {
        $entity = New Atleta(
            nome: 'ÁÉÊ Çção',
            dtNascimento: new DateTime('2001-01-01'),
        );
        
        $response = $this->repository->create($entity);

        $this->assertInstanceOf(Atleta::class, $response);
        $this->assertDatabaseHas('atletas', [
            'nome' => $entity->nome,
            'dtNascimento' => $entity->dtNascimento(),
            'created_at' => $entity->criadoEm(),
        ]);
    }

    public function testRead()
    {
        $atletaA = AtletaModel::factory()->create();
        $atletaB = AtletaModel::factory()->create();

        $responseA = $this->repository->read($atletaA->id);
        $this->assertInstanceOf(Atleta::class, $responseA);
        $this->assertEquals($atletaA->id, $responseA->id);
        $this->assertEquals($atletaA->nome, $responseA->nome);
        $this->assertEquals($atletaA->dtNascimento, $responseA->dtNascimento);

        $responseB = $this->repository->read($atletaB->id);
        $this->assertInstanceOf(Atleta::class, $responseB);
        $this->assertEquals($atletaB->id, $responseB->id);
        $this->assertEquals($atletaB->nome, $responseB->nome);
        $this->assertEquals($atletaB->dtNascimento, $responseB->dtNascimento);
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
        $atletaA = AtletaModel::factory()->create();
        $this->assertDatabaseHas('atletas', [
            'nome' => $atletaA->nome,
            'dtNascimento' => $atletaA->dtNascimento
        ]);

        $responseA = $this->repository->read($atletaA->id);
        $responseA->alterar(
            $responseA->nome . 'ALTERADO',
            AtletaModel::factory()->valid_dtNascimento(),
        );
        $this->repository->update($responseA);
        $this->assertDatabaseHas('atletas', [
            'nome' => $responseA->nome,
            'dtNascimento' => $responseA->dtNascimento,
        ]);

        $this->assertDatabaseMissing('atletas', [
            'nome' => $atletaA->nome,
        ]);
        $this->assertDatabaseMissing('atletas', [
            'dtNascimento' => $atletaA->dtNascimento,
        ]);
    }

    public function testUpdateNotFound()
    {
        $entity = New Atleta(
            nome: 'nome qualquer',
            dtNascimento: AtletaModel::factory()->valid_dtNascimento(),
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
        $atletaA = AtletaModel::factory()->create();
        $this->assertDatabaseHas('atletas', [
            'nome' => $atletaA->nome,
            'dtNascimento' => $atletaA->dtNascimento
        ]);

        $this->repository->delete($atletaA->id);
        $this->assertSoftDeleted($atletaA);
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

    public function testList()
    {
        $atletas = AtletaModel::factory()->count(20)->create();
        $response = $this->repository->list();
        $this->assertEquals(count($atletas), count($response));
        // TODO: checar o conteudo de $atletas e $response
    }

    public function testListOrderByNomeAsc()
    {
        $atletas = AtletaModel::factory()->count(20)->create();
        $arrAtletas = $this->repository->list(
            order: '{"nome": "asc"}',
        );
        $this->assertEquals(count($atletas), count($arrAtletas));

        $arrNomes = [];
        foreach ($atletas as $key => $atleta) {
            array_push($arrNomes, $atleta->nome);
        }
        sort($arrNomes);

        foreach ($arrAtletas as $key => $value) {
            $this->assertEquals($arrNomes[$key], $arrAtletas[$key]['nome']);
        }
    }

    public function testListFilterByNome()
    {
        $atletas = AtletaModel::factory()->count(50)->create();

        $arrAtletas = $this->repository->list(
            filter: $atletas[0]->nome
        );
        $this->assertGreaterThanOrEqual(1, count($arrAtletas));
        foreach ($arrAtletas as $key => $value) {
            $this->assertEquals($atletas[0]->nome, $arrAtletas[$key]['nome']);
        }

        $arrAtletas = $this->repository->list(
            filter: $atletas[count($atletas)-1]->nome
        );
        $this->assertGreaterThanOrEqual(1, count($arrAtletas));
        foreach ($arrAtletas as $key => $value) {
            $this->assertEquals(
                $atletas[count($atletas)-1]->nome,
                $arrAtletas[$key]['nome'],
            );
        }
    }

    public function testListFilterNotFound()
    {
        $atletas = AtletaModel::factory()->count(50)->create();
        $arrAtletas = $this->repository->list(
            filter: 'xyz'
        );
        $this->assertEquals(0, count($arrAtletas));
    }    

    public function testListOrderByDtNascimentoAsc()
    {
        $atletas = AtletaModel::factory()->count(20)->create();
        $arrAtletas = $this->repository->list(
            order: '{"dtNascimento": "asc"}',
        );
        $this->assertEquals(count($atletas), count($arrAtletas));

        $arrDatas = [];
        foreach ($atletas as $key => $atleta) {
            array_push($arrDatas, $atleta->dtNascimento);
        }
        sort($arrDatas);

        foreach ($arrAtletas as $key => $value) {
            $this->assertEquals($arrDatas[$key]->format('Y-m-d H:i:s'), $arrAtletas[$key]['dtNascimento']);
        }
    }

}
