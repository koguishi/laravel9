<?php

namespace Tests\Unit\domain\entity;

use core\domain\entity\Video;
use core\domain\exception\EntityValidationException;
use core\domain\valueobject\Uuid;
use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class VideoTest extends TestCase
{
    public function testContructVideoNovo()
    {
        $titulo = 'Título do vídeo';
        $descricao = 'Descrição do vídeo';
        $dtFilmagem = new DateTime('2024-10-08');

        $video = new Video(
            titulo: $titulo,
            descricao: $descricao,
            dtFilmagem: $dtFilmagem,
        );
        $this->assertEquals($titulo, $video->titulo);
        $this->assertEquals($descricao, $video->descricao);
        $this->assertEquals($dtFilmagem, $video->dtFilmagem);
        $this->assertNotEmpty($video->id());
        $this->assertNotEmpty($video->criadoEm);
    }

    public function testContructVideoExistente()
    {
        $uuid = (string) RamseyUuid::uuid4();

        $titulo = 'titulo do Video';
        $descricao = 'descricao do Video';
        $dtFilmagem = new DateTime(date('Y-m-d'));

        $video = new Video(
            titulo: $titulo,
            descricao: $descricao,
            dtFilmagem: $dtFilmagem,
            id: new Uuid($uuid),
        );
        $this->assertEquals($titulo, $video->titulo);
        $this->assertEquals($descricao, $video->descricao);
        $this->assertEquals($dtFilmagem, $video->dtFilmagem);
        $this->assertEquals($uuid, $video->id());
        // $this->assertEquals($criadoEm, $video->criadoEm);
    }

    public function testVincularCategoria()
    {
        $categoriaId = (string) RamseyUuid::uuid4();

        $titulo = 'titulo do Video';
        $descricao = 'descricao do Video';
        $dtFilmagem = new DateTime(date('Y-m-d'));

        $video = new Video(
            titulo: $titulo,
            descricao: $descricao,
            dtFilmagem: $dtFilmagem,
        );
        $this->assertCount(0, $video->categoriaIds);

        $video->vincularCategoria(categoriaId: $categoriaId);
        $this->assertCount(1, $video->categoriaIds);

        // vincular a mesma categoria
        $video->vincularCategoria(categoriaId: $categoriaId);
        // então deve continuar com 1 item
        $this->assertCount(1, $video->categoriaIds);

        // vincular outra categoria
        $video->vincularCategoria(categoriaId: (string) RamseyUuid::uuid4());
        $this->assertCount(2, $video->categoriaIds);
    }

    public function testDesvincularCategoria()
    {
        $categoriaId = (string) RamseyUuid::uuid4();

        $titulo = 'titulo do Video';
        $descricao = 'descricao do Video';
        $dtFilmagem = new DateTime(date('Y-m-d'));

        $video = new Video(
            titulo: $titulo,
            descricao: $descricao,
            dtFilmagem: $dtFilmagem,
        );

        $video->vincularCategoria(categoriaId: $categoriaId);
        $this->assertCount(1, $video->categoriaIds);

        $video->desvincularCategoria(categoriaId: $categoriaId);
        $this->assertCount(0, $video->categoriaIds);
    }

    public function testVincularAtleta()
    {
        $atletaId = (string) RamseyUuid::uuid4();

        $titulo = 'titulo do Video';
        $descricao = 'descricao do Video';
        $dtFilmagem = new DateTime(date('Y-m-d'));

        $video = new Video(
            titulo: $titulo,
            descricao: $descricao,
            dtFilmagem: $dtFilmagem,
        );
        $this->assertCount(0, $video->atletaIds);

        $video->vincularAtleta(atletaId: $atletaId);
        $this->assertCount(1, $video->atletaIds);

        // vincular o mesmo atleta
        $video->vincularAtleta(atletaId: $atletaId);
        // então deve continuar com 1 item
        $this->assertCount(1, $video->atletaIds);

        // vincular outra categoria
        $video->vincularAtleta(atletaId: (string) RamseyUuid::uuid4());
        $this->assertCount(2, $video->atletaIds);
    }

    public function testDesvincularAtleta()
    {
        $atletaId = (string) RamseyUuid::uuid4();

        $titulo = 'titulo do Video';
        $descricao = 'descricao do Video';
        $dtFilmagem = new DateTime(date('Y-m-d'));

        $video = new Video(
            titulo: $titulo,
            descricao: $descricao,
            dtFilmagem: $dtFilmagem,
        );

        $video->vincularAtleta(atletaId: $atletaId);
        $this->assertCount(1, $video->atletaIds);

        $video->desvincularAtleta(atletaId: $atletaId);
        $this->assertCount(0, $video->atletaIds);
    }

    // public function testExceptionNomeMenorQue3()
    // {
    //     try {
    //         new Atleta(
    //             nome: 'AA',
    //             dtNascimento: new DateTime('2000-01-01'),
    //         );

    //         $this->assertTrue(false);
    //     } catch (\Throwable $th) {
    //         $this->assertInstanceOf(EntityValidationException::class, $th);
    //         $this->assertEquals("Nome deve ter no mínimo 3 caracteres", $th->getMessage());
    //     }
    // }

    // public function testExceptionNomeMaiorQue100()
    // {
    //     try {
    //         new Atleta(
    //             nome: random_bytes(101),
    //             dtNascimento: new DateTime('2000-01-01'),
    //         );

    //         $this->assertTrue(false);
    //     } catch (\Throwable $th) {
    //         $this->assertInstanceOf(EntityValidationException::class, $th);
    //         $this->assertEquals("Nome deve ter no máximo 100 caracteres", $th->getMessage());
    //     }
    // }
    
    // public function testExceptionDtNascimentoMaiorQueHoje()
    // {
    //     try {
    //         $video = new Atleta(
    //             nome: random_bytes(100),
    //             dtNascimento: new DateTime(today()),
    //         );

    //         $this->assertTrue(false);
    //     } catch (\Throwable $th) {
    //         $this->assertInstanceOf(EntityValidationException::class, $th);
    //         $this->assertEquals("Data de nascimento não pode ser igual ou posterior a hoje", $th->getMessage());
    //     }
    // }

    // public function testExceptionDtNascimentoMenorQue100Anos()
    // {
    //     try {
    //         $video = new Atleta(
    //             nome: random_bytes(100),
    //             dtNascimento: $this->dataAtualMenos100Anos(),
    //         );

    //         $this->assertTrue(false);
    //     } catch (\Throwable $th) {
    //         $this->assertInstanceOf(EntityValidationException::class, $th);
    //         $this->assertEquals("Data de nascimento não pode ser anterior a 100 anos", $th->getMessage());
    //     }
    // }

    // public function testAlterarAluno() {
    //     $uuid = (string) RamseyUuid::uuid4();

    //     $titulo = 'Atleta já cadastrado';
    //     $criadoEm = new DateTime(date('Y-m-d H:i:s'));
    //     $dtFilmagem = new DateTime('1974-10-08');

    //     $video = new Atleta(
    //         nome: $titulo,
    //         dtNascimento: $dtFilmagem,
    //         id: new Uuid($uuid),
    //         criadoEm: $criadoEm
    //     );
    //     $tituloAlterado = 'Nome Alterado';
    //     $dtFilmagemAlterado = new DateTime('2004-10-08');

    //     $video->alterar(
    //         nome: $tituloAlterado,
    //         dtNascimento: $dtFilmagemAlterado,
    //     );

    //     $this->assertEquals($tituloAlterado, $video->titulo);
    //     $this->assertEquals($dtFilmagemAlterado, $video->dtFilmagem);
    //     $this->assertEquals($uuid, $video->id());
    //     $this->assertEquals($criadoEm, $video->criadoEm);
    // }

    // public function testExceptionAlterarNomeMenorQue3()
    // {
    //     try {
    //         $video = new Atleta(
    //             nome: 'AAA',
    //             dtNascimento: new DateTime('2000-01-01'),
    //         );
    //         $video->alterar(nome: 'AA');

    //         $this->assertTrue(false);
    //     } catch (\Throwable $th) {
    //         $this->assertInstanceOf(EntityValidationException::class, $th);
    //         $this->assertEquals("Nome deve ter no mínimo 3 caracteres", $th->getMessage());
    //     }
    // }

    // public function testExceptionAlterarNomeMaiorQue100()
    // {
    //     try {
    //         $video = new Atleta(
    //             nome: 'AAA',
    //             dtNascimento: new DateTime('2000-01-01'),
    //         );
    //         $video->alterar(nome: random_bytes(101));

    //         $this->assertTrue(false);
    //     } catch (\Throwable $th) {
    //         $this->assertInstanceOf(EntityValidationException::class, $th);
    //         $this->assertEquals("Nome deve ter no máximo 100 caracteres", $th->getMessage());
    //     }
    // }

    // public function testExceptionAlterarDtNascimentoMaiorQueHoje()
    // {
    //     try {
    //         $video = new Atleta(
    //             nome: random_bytes(100),
    //             dtNascimento: new DateTime('2000-01-01'),
    //         );
    //         $video->alterar(dtNascimento: new DateTime(today()));

    //         $this->assertTrue(false);
    //     } catch (\Throwable $th) {
    //         $this->assertInstanceOf(EntityValidationException::class, $th);
    //         $this->assertEquals("Data de nascimento não pode ser igual ou posterior a hoje", $th->getMessage());
    //     }
    // }

    // public function testExceptionAlterarDtNascimentoMenorQue100anos()
    // {
    //     try {
    //         $video = new Atleta(
    //             nome: random_bytes(100),
    //             dtNascimento: new DateTime('2000-01-01'),
    //         );

    //         $video->alterar(dtNascimento: $this->dataAtualMenos100Anos());

    //         $this->assertTrue(false);
    //     } catch (\Throwable $th) {
    //         $this->assertInstanceOf(EntityValidationException::class, $th);
    //         $this->assertEquals("Data de nascimento não pode ser anterior a 100 anos", $th->getMessage());
    //     }
    // }

    // private function dataAtualMenos100Anos() : DateTime
    // {
    //     // Cria um objeto DateTime com a data atual
    //     $data = new DateTime(today());
    //     // Subtrai 100 anos
    //     return $data->modify('-100 years');
    // }

}
