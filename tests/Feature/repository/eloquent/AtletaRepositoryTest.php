<?php

namespace Tests\Feature\repository\eloquent;

use App\Models\Atleta as AtletaModel;
use app\repository\eloquent\AtletaRepository;
use core\domain\repository\AtletaRepositoryInterface;
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

}
