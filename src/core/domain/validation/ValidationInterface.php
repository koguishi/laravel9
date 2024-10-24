<?php

namespace core\domain\validation;

use core\domain\entity\Entity;

interface ValidationInterface
{
    public function validate(Entity $entity): void;
}
