<?php

namespace Tests\Feature\repository\eloquent;

use App\Models\Atleta as AtletaModel;
use app\repository\eloquent\AtletaRepository;
use core\domain\entity\Atleta;
use core\domain\repository\AtletaRepositoryInterface;
use DateTime;
use Tests\TestCase;

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
}
