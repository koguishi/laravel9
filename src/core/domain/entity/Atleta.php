<?php

namespace core\domain\entity;

use core\domain\entity\traits\MagicMethodsTrait;
use core\domain\validation\DomainValidation;
use core\domain\valueobject\Uuid;
use DateTime;

class Atleta
{
    use MagicMethodsTrait;

    public function __construct(
        protected string $nome,
        protected DateTime $dtNascimento,
        protected ?Uuid $id = null,
        protected ?DateTime $criadoEm = null,
    ) {
        $this->id = $this->id ?? Uuid::random();
        $this->criadoEm = $this->criadoEm ?? new DateTime();
        $this->validate();
    }

    public function dtNascimento(): string
    {
        return $this->dtNascimento->format('Y-m-d H:i:s');
    }    

    private function validate()
    {
        $nomeMinLen = 3;
        DomainValidation::strMinLen($this->nome, $nomeMinLen, "Nome deve ter no mínimo {$nomeMinLen} caracteres");
        $nomeMaxLen = 100;
        DomainValidation::strMaxLen($this->nome, $nomeMaxLen, "Nome deve ter no máximo {$nomeMaxLen} caracteres");
        DomainValidation::notAfterToday($this->dtNascimento, "Data de nascimento não pode ser posterior a hoje");
        DomainValidation::notBefore100Years($this->dtNascimento, "Data de nascimento não pode ser anterior a 100 anos");
    }

    public function alterar(
        ?string $nome = null,
        ?DateTime $dtNascimento = null,
    )
    {
        $this->nome = $nome ?? $this->nome;
        $this->dtNascimento = $dtNascimento ?? $this->dtNascimento;
        $this->validate();
    }
}
