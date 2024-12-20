<?php

namespace Tests\Feature\repository\eloquent;

use App\Models\Atleta as AtletaModel;
use app\repository\eloquent\AtletaRepository;
use core\domain\entity\Atleta;
use core\domain\exception\AlreadyExistsException;
use core\domain\exception\NotFoundException;
use core\domain\repository\AtletaRepositoryInterface;
use core\domain\repository\PaginationInterface;
use DateTime;
use Tests\TestCase;
use Throwable;

class AtletaRepositoryTest extends TestCase
{
    /**
     * @var AtletaRepository
     */
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
            'dt_nascimento' => $entity->dtNascimento(),
            'created_at' => $entity->criadoEm(),
        ]);
    }

    public function testCreateAlreadyExists()
    {
        $atleta = AtletaModel::factory()->create();
        $entity = New Atleta(
            nome: $atleta->nome,
            dtNascimento: new DateTime('2001-01-01'),
        );
        try {
            $this->repository->create($entity);
    
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(AlreadyExistsException::class, $th);
        }
    }    

    public function testRead()
    {
        $atletaA = AtletaModel::factory()->create();
        $atletaB = AtletaModel::factory()->create();

        $responseA = $this->repository->read($atletaA->id);
        $this->assertInstanceOf(Atleta::class, $responseA);
        $this->assertEquals($atletaA->id, $responseA->id);
        $this->assertEquals($atletaA->nome, $responseA->nome);
        $this->assertEquals($atletaA->dt_nascimento, $responseA->dtNascimento);

        $responseB = $this->repository->read($atletaB->id);
        $this->assertInstanceOf(Atleta::class, $responseB);
        $this->assertEquals($atletaB->id, $responseB->id);
        $this->assertEquals($atletaB->nome, $responseB->nome);
        $this->assertEquals($atletaB->dt_nascimento, $responseB->dtNascimento);
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
            'dt_nascimento' => $atletaA->dt_nascimento
        ]);

        $responseA = $this->repository->read($atletaA->id);
        $responseA->alterar(
            $responseA->nome . 'ALTERADO',
            AtletaModel::factory()->valid_dtNascimento(),
        );
        $this->repository->update($responseA);
        $this->assertDatabaseHas('atletas', [
            'nome' => $responseA->nome,
            'dt_nascimento' => $responseA->dtNascimento,
        ]);

        $this->assertDatabaseMissing('atletas', [
            'nome' => $atletaA->nome,
        ]);
        $this->assertDatabaseMissing('atletas', [
            'dtNascimento' => $atletaA->dt_nascimento,
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
            'dt_nascimento' => $atletaA->dt_nascimento
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

    public function testGetIds()
    {
        $atletas = AtletaModel::factory()->count(20)->create();
        $response = $this->repository->getIds([$atletas[0]->id]);
        $this->assertCount(1, $response);
        $this->assertEquals($atletas[0]->id, $response[0]);

        $response = $this->repository->getIds([
            $atletas[1]->id,
            $atletas[2]->id,
        ]);
        $this->assertCount(2, $response);
        $this->assertContains($atletas[1]->id, $response);
        $this->assertContains($atletas[2]->id, $response);
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

    public function testListOrderByDtNascimentoAsc()
    {
        $atletas = AtletaModel::factory()->count(20)->create();
        $arrAtletas = $this->repository->list(
            order: '{"dt_nascimento": "asc"}',
        );
        $this->assertEquals(count($atletas), count($arrAtletas));

        $arrDatas = [];
        foreach ($atletas as $key => $atleta) {
            array_push($arrDatas, $atleta->dt_nascimento);
        }
        sort($arrDatas);

        foreach ($arrAtletas as $key => $value) {
            $this->assertEquals($arrDatas[$key]->format('Y-m-d H:i:s'), $arrAtletas[$key]['dt_nascimento']);
        }
    }

    public function testListFilterByNome()
    {
        $atletas = AtletaModel::factory()->count(50)->create();

        $arrAtletas = $this->repository->list(
            filter_nome: $atletas[0]->nome
        );
        $this->assertGreaterThanOrEqual(1, count($arrAtletas));
        foreach ($arrAtletas as $key => $value) {
            $this->assertEquals($atletas[0]->nome, $arrAtletas[$key]['nome']);
        }

        $arrAtletas = $this->repository->list(
            filter_nome: $atletas[count($atletas)-1]->nome
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
        AtletaModel::factory()->count(50)->create();
        $arrAtletas = $this->repository->list(
            filter_nome: 'xyz'
        );
        $this->assertEquals(0, count($arrAtletas));
    }    

    public function testListFilterByDtNascimento()
    {
        $arrDatas = [
            '1999-12-29',
            '1999-12-30',
            '1999-12-31',
            '2000-01-01',
            '2000-01-02',
            '2000-01-03',
        ];
        AtletaModel::factory()->create(['dt_nascimento' => $arrDatas[0]]);
        AtletaModel::factory()->create(['dt_nascimento' => $arrDatas[1]]);
        AtletaModel::factory()->create(['dt_nascimento' => $arrDatas[2]]);
        AtletaModel::factory()->create(['dt_nascimento' => $arrDatas[3]]);
        AtletaModel::factory()->create(['dt_nascimento' => $arrDatas[4]]);
        AtletaModel::factory()->create(['dt_nascimento' => $arrDatas[5]]);

        $this->assertDatabaseHas('atletas', ['dt_nascimento' => $arrDatas[0]]);
        $this->assertDatabaseHas('atletas', ['dt_nascimento' => $arrDatas[1]]);
        $this->assertDatabaseHas('atletas', ['dt_nascimento' => $arrDatas[2]]);
        $this->assertDatabaseHas('atletas', ['dt_nascimento' => $arrDatas[3]]);
        $this->assertDatabaseHas('atletas', ['dt_nascimento' => $arrDatas[4]]);
        $this->assertDatabaseHas('atletas', ['dt_nascimento' => $arrDatas[5]]);

        $arrAtletas = $this->repository->list(
            filter_dtNascimento_inicial: new DateTime($arrDatas[0]),
            filter_dtNascimento_final: new DateTime($arrDatas[1]),
        );
        $this->assertCount(2, $arrAtletas);

        $arrAtletas = $this->repository->list(
            filter_dtNascimento_inicial: new DateTime($arrDatas[1]),
            filter_dtNascimento_final: null,
        );
        $this->assertCount(5, $arrAtletas);

        $arrAtletas = $this->repository->list(
            filter_dtNascimento_inicial: null,
            filter_dtNascimento_final: new DateTime($arrDatas[2]),
        );
        $this->assertCount(3, $arrAtletas);

        $arrAtletas = $this->repository->list(
            filter_dtNascimento_inicial: new DateTime($arrDatas[3]),
            filter_dtNascimento_final: new DateTime($arrDatas[3]),
        );
        $this->assertCount(1, $arrAtletas);

        $primeiraData = new DateTime($arrDatas[0]);
        $ultimaData = new DateTime($arrDatas[5]);

        $primeiraData->modify('-1 days');
        $ultimaData->modify('+1 days');

        $arrAtletas = $this->repository->list(
            filter_dtNascimento_inicial: $ultimaData,
            filter_dtNascimento_final: null,
        );
        $this->assertCount(0, $arrAtletas);

        $arrAtletas = $this->repository->list(
            filter_dtNascimento_inicial: null,
            filter_dtNascimento_final: $primeiraData,
        );
        $this->assertCount(0, $arrAtletas);
    }

    public function testPaginate()
    {
        AtletaModel::factory(count: 20)->create();

        $response = $this->repository->paginate();

        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(15, $response->items());
    }

    public function testPaginateOrderByNomeAsc()
    {
        $atletas = AtletaModel::factory()->count(20)->create();
        $response = $this->repository->paginate(
            order: '{"nome": "asc"}',
        );
        $this->assertCount(15, $response->items());

        $arrNomes = [];
        foreach ($atletas as $key => $atleta) {
            array_push($arrNomes, $atleta->nome);
        }
        sort($arrNomes);

        foreach ($response->items() as $key => $value) {
            $this->assertEquals($arrNomes[$key], $value->nome);
        }
    }

    public function testPaginateOrderByDtNascimentoAsc()
    {
        $atletas = AtletaModel::factory()->count(20)->create();
        $response = $this->repository->paginate(
            order: '{"dt_nascimento": "asc"}',
        );
        $this->assertCount(15, $response->items());

        $arrDatas = [];
        foreach ($atletas as $key => $atleta) {
            array_push($arrDatas, $atleta->dt_nascimento);
        }
        sort($arrDatas);

        foreach ($response->items() as $key => $value) {
            $this->assertEquals(
                $arrDatas[$key]->format('Y-m-d H:i:s'),
                $value->dt_nascimento
            );
        }
    }


    public function testPaginateFilterByNome()
    {
        AtletaModel::factory()->count(20)->create();
        $atleta = AtletaModel::factory()->create();

        $response = $this->repository->paginate(
            filter_nome: $atleta->nome
        );

        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(1, $response->items());
        $this->assertEquals($atleta->nome, $response->items()[0]->nome);
    }

    public function testPaginateFilterNotFound()
    {
        AtletaModel::factory()->count(50)->create();
        $response = $this->repository->paginate(
            filter_nome: 'xyz'
        );
        $this->assertEquals(0, count($response->items()));
    }

    public function testPaginateFilterByDtNascimento()
    {
        $dtNascimento1 = new DateTime('1999-01-23');
        $dtNascimento2 = new DateTime('2011-04-15');
        $dtNascimento3 = new DateTime('2021-09-30');
        AtletaModel::factory(
            count: 7,
            state: ['dt_nascimento' => $dtNascimento1]
        )->create();

        AtletaModel::factory(
            count: 7,
            state: ['dt_nascimento' => $dtNascimento2]
        )->create();

        AtletaModel::factory(
            count: 7,
            state: ['dt_nascimento' => $dtNascimento3]
        )->create();

        $response = $this->repository->paginate(
            filter_dtNascimento_inicial: $dtNascimento1,
            filter_dtNascimento_final: $dtNascimento1,
        );
        $this->assertCount(7, $response->items());

        $response = $this->repository->paginate(
            filter_dtNascimento_inicial: $dtNascimento1,
            filter_dtNascimento_final: $dtNascimento2,
        );
        $this->assertCount(14, $response->items());

        $dtNascimento1->modify('-1 days');
        $dtNascimento3->modify('+1 days');

        $response = $this->repository->paginate(
            filter_dtNascimento_inicial: $dtNascimento3
        );
        $this->assertCount(0, $response->items());

        $response = $this->repository->paginate(
            filter_dtNascimento_final: $dtNascimento1
        );
        $this->assertCount(0, $response->items());
    }

    public function testPaginateFilterByDtNascimentoPagX()
    {
        $dtNascimento1 = new DateTime('1999-01-23');
        $dtNascimento2 = new DateTime('2011-04-15');
        AtletaModel::factory(
            count: 7,
            state: ['dt_nascimento' => $dtNascimento1]
        )->create();

        AtletaModel::factory(
            count: 7,
            state: ['dt_nascimento' => $dtNascimento2]
        )->create();

        AtletaModel::factory(
            count: 10,
            state: ['dt_nascimento' => $dtNascimento1]
        )->create();

        // total de 17 items com $dtNascimento1
        $response = $this->repository->paginate(
            page: 2,
            filter_dtNascimento_inicial: $dtNascimento1,
            filter_dtNascimento_final: $dtNascimento1,
        );
        // como não passamos o totalPage ele assume o padrão que é 15
        // portanto, 17 - 15 = 2 itens na segunda página
        $this->assertCount(2, $response->items());

        // total de 7 items com $dtNascimento2
        $response = $this->repository->paginate(
            perPage: 3,
            page: 3,
            filter_dtNascimento_inicial: $dtNascimento2,
            filter_dtNascimento_final: $dtNascimento2,
        );
        $this->assertCount(1, $response->items());
    }
}
