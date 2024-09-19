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
                dtNascimento: new DateTime('2000-01-01'),
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
                dtNascimento: new DateTime('2000-01-01'),
            );

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
            $this->assertEquals("Nome deve ter no máximo 100 caracteres", $th->getMessage());
        }
    }
    
    public function testExceptionDtNascimentoMaiorQueHoje()
    {
        try {
            $atleta = new Atleta(
                nome: random_bytes(100),
                dtNascimento: new DateTime(today()),
            );

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
            $this->assertEquals("Data de nascimento não pode ser posterior a hoje", $th->getMessage());
        }
    }

    public function testExceptionDtNascimentoMenorQue100Anos()
    {
        try {
            $atleta = new Atleta(
                nome: random_bytes(100),
                dtNascimento: $this->dataAtualMenos100Anos(),
            );

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
            $this->assertEquals("Data de nascimento não pode ser anterior a 100 anos", $th->getMessage());
        }
    }

    public function testAlterarAluno() {
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
        $nomeAlterado = 'Nome Alterado';
        $dtNascimentoAlterado = new DateTime('2004-10-08');

        $atleta->alterar(
            nome: $nomeAlterado,
            dtNascimento: $dtNascimentoAlterado,
        );

        $this->assertEquals($nomeAlterado, $atleta->nome);
        $this->assertEquals($dtNascimentoAlterado, $atleta->dtNascimento);
        $this->assertEquals($uuid, $atleta->id());
        $this->assertEquals($criadoEm, $atleta->criadoEm);
    }

    public function testExceptionAlterarNomeMenorQue3()
    {
        try {
            $atleta = new Atleta(
                nome: 'AAA',
                dtNascimento: new DateTime('2000-01-01'),
            );
            $atleta->alterar(nome: 'AA');

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
            $this->assertEquals("Nome deve ter no mínimo 3 caracteres", $th->getMessage());
        }
    }

    public function testExceptionAlterarNomeMaiorQue100()
    {
        try {
            $atleta = new Atleta(
                nome: 'AAA',
                dtNascimento: new DateTime('2000-01-01'),
            );
            $atleta->alterar(nome: random_bytes(101));

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
            $this->assertEquals("Nome deve ter no máximo 100 caracteres", $th->getMessage());
        }
    }

    public function testExceptionAlterarDtNascimentoMaiorQueHoje()
    {
        try {
            $atleta = new Atleta(
                nome: random_bytes(100),
                dtNascimento: new DateTime('2000-01-01'),
            );
            $atleta->alterar(dtNascimento: new DateTime(today()));

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
            $this->assertEquals("Data de nascimento não pode ser posterior a hoje", $th->getMessage());
        }
    }

    public function testExceptionAlterarDtNascimentoMenorQue100anos()
    {
        try {
            $atleta = new Atleta(
                nome: random_bytes(100),
                dtNascimento: new DateTime('2000-01-01'),
            );

            $atleta->alterar(dtNascimento: $this->dataAtualMenos100Anos());

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
            $this->assertEquals("Data de nascimento não pode ser anterior a 100 anos", $th->getMessage());
        }
    }

    private function dataAtualMenos100Anos() : DateTime
    {
        // Cria um objeto DateTime com a data atual
        $data = new DateTime(today());
        // Subtrai 100 anos
        return $data->modify('-100 years');
    }

}
