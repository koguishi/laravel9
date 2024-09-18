<?php

namespace Tests\Unit\domain\entity;

use core\domain\entity\Atleta;
use core\domain\exception\EntityValidationException;
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

    public function testExceptionNomeMenorQue3()
    {
        try {
            new Atleta(
                nome: 'AA',
                dtNascimento: new DateTime(),
            );

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
            $this->assertEquals("Nome deve ter no mínimo 3 caracteres", $th->getMessage());
        }
    }

    public function testExceptionNomeMaiorQue100()
    {
        try {
            new Atleta(
                nome: random_bytes(101),
                dtNascimento: new DateTime(),
            );

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
            $this->assertEquals("Nome deve ter no máximo 100 caracteres", $th->getMessage());
        }
    }

}