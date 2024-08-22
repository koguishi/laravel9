<?php

namespace core\domain\entity;

use core\domain\entity\traits\MagicMethodsTrait;
use core\domain\validation\DomainValidation;
use core\domain\valueobject\Uuid;
use DateTime;

class Categoria
{
    use MagicMethodsTrait;

    public function __construct(
        protected Uuid|string $id = '',
        protected string $nome = '',
        protected string $descricao = '',
        protected bool $ativo = true,
        protected DateTime|string $criadoEm = '',
    )
    {
        $this->id = $this->id ? new Uuid($this->id) : Uuid::random();
        $this->criadoEm = $this->criadoEm ? new DateTime($this->criadoEm) : new DateTime();
        $this->validate();
    }

    private function validate()
    {
        $nomeMinLen = 3;
        DomainValidation::strMinLen($this->nome, $nomeMinLen, 'Nome deve ter no mínimo {$nomeMinLen} caracteres');
        $nomeMaxLen = 100;
        DomainValidation::strMaxLen($this->nome, $nomeMaxLen, 'Nome deve ter no máximo {$nomeMaxLen} caracteres');
        $descricaoMaxLen = 255;
        DomainValidation::strCanNullButMaxLen($this->descricao, $descricaoMaxLen, 'Descrição deve ter no máximo {$descricaoMaxLen} caracteres');
    }

    public function desativar()
    {
        $this->ativo = false;
    }

    public function ativar()
    {
        $this->ativo = true;
    }

    public function alterar(
        string $nome,
        string $descricao = null,
    )
    {
        $this->nome = $nome;
        $this->descricao = $descricao ?? $this->descricao;

        $this->validate();
    }
}