<?php

namespace core\domain\entity;

use core\domain\entity\traits\MagicMethodsTrait;
use core\domain\validation\DomainValidation;
use core\domain\valueobject\Uuid;
use DateTime;

class Video
{
    use MagicMethodsTrait;

    public function __construct(
        protected string $titulo,
        protected string $descricao,
        protected DateTime $dtFilmagem,
        protected ?Uuid $id = null,
        protected ?DateTime $criadoEm = null,
    ) {
        $this->id = $this->id ?? Uuid::random();
        $this->criadoEm = $this->criadoEm ?? new DateTime();
        $this->validate();
    }

    public function dtFilmagem(): string
    {
        return $this->dtFilmagem->format('Y-m-d');
    }    

    private function validate()
    {
        $tituloMinLen = 3;
        DomainValidation::strMinLen($this->titulo, $tituloMinLen, "Titulo deve ter no mínimo {$tituloMinLen} caracteres");
        $tituloMaxLen = 100;
        DomainValidation::strMaxLen($this->titulo, $tituloMaxLen, "Titulo deve ter no máximo {$tituloMaxLen} caracteres");
        DomainValidation::notAfterToday($this->dtFilmagem, "Data de filmagem não pode ser posterior a hoje");
        DomainValidation::notBefore100Years($this->dtFilmagem, "Data de filmagem não pode ser anterior a 100 anos");
    }

    public function alterar(
        ?string $titulo = null,
        ?string $descricao = null,
        ?DateTime $dtFilmagem = null,
    )
    {
        $this->titulo = $titulo ?? $this->titulo;
        $this->descricao = $descricao ?? $this->descricao;
        $this->dtFilmagem = $dtFilmagem ?? $this->dtFilmagem;
        $this->validate();
    }
}
