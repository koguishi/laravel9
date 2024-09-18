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

    private function validate()
    {
        $nomeMinLen = 3;
        DomainValidation::strMinLen($this->nome, $nomeMinLen, "Nome deve ter no mínimo {$nomeMinLen} caracteres");
        $nomeMaxLen = 100;
        DomainValidation::strMaxLen($this->nome, $nomeMaxLen, "Nome deve ter no máximo {$nomeMaxLen} caracteres");
        // DomainValidation::notNull($this->dtNascimento)
    }
}