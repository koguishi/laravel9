<?php

namespace core\domain\entity;

use core\domain\entity\traits\MagicMethodsTrait;
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
    }
}