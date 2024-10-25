<?php

namespace core\domain\repository;

use core\domain\entity\Entity;
use DateTime;

interface EntityRepositoryInterface
{
    public function create(Entity $entity): Entity;
    public function read(string $id): Entity;
    public function update(Entity $entity): Entity;
    public function delete(string $id): bool;
}
