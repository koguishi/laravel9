<?php

namespace Tests\Unit\domain\entity;

use core\domain\entity\Atleta;
use core\domain\valueobject\Uuid;
use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class AtletaTest extends TestCase
{
    public function testContructAtletaNovo()
    {
        $nome = 'Atleta a ser Inserido';
        $dtNascimento = new DateTime('1974-10-08');

        $atleta = new Atleta(
            nome: $nome,
            dtNascimento: $dtNascimento,
        );
        $this->assertEquals($nome, $atleta->nome);
        $this->assertEquals($dtNascimento, $atleta->dtNascimento);
        $this->assertNotEmpty($atleta->id());
        $this->assertNotEmpty($atleta->criadoEm);
    }

    public function testContructAtletaExistente()
    {
        $uuid = (string) RamseyUuid::uuid4();

        $nome = 'Atleta já cadastrado';
        $criadoEm = new DateTime(date('Y-m-d H:i:s'));
        $dtNascimento = new DateTime('1974-10-08');

        $atleta = new Atleta(
            nome: $nome,
            dtNascimento: $dtNascimento,
            id: new Uuid($uuid),
            criadoEm: $criadoEm
        );
        $this->assertEquals($nome, $atleta->nome);
        $this->assertEquals($dtNascimento, $atleta->dtNascimento);
        $this->assertEquals($uuid, $atleta->id());
        $this->assertEquals($criadoEm, $atleta->criadoEm);
    }
}