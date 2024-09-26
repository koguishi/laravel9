<?php

namespace app\repository\eloquent;

use App\Models\Atleta as AtletaModel;
use core\domain\entity\Atleta;
use core\domain\repository\AtletaRepositoryInterface;
use core\domain\repository\PaginationInterface;
use core\domain\valueobject\Uuid;
use DateTime;

class AtletaRepository implements AtletaRepositoryInterface
{
    protected $model;

    public function __construct(AtletaModel $model) {
        $this->model = $model;
    }

    private function toEntity(object $object): Atleta {
        $entity = new Atleta(
            id: new Uuid($object->id),
            nome: $object->nome,
            dtNascimento: $object->dtNascimento,
        );

        return $entity;
    }    

    public function create(Atleta $entity): Atleta
    {
        $atletaDb = $this->model->create([
            'id' => $entity->id(),
            'nome' => $entity->nome,
            'dtNascimento' => $entity->dtNascimento,
        ]);
        return $this->toEntity($atletaDb);
    }
    
    public function read(string $id): Atleta
    {
        return new Atleta(nome: '', dtNascimento: new DateTime());
    }

    public function update(Atleta $entity): Atleta
    {
        return new Atleta(nome: '', dtNascimento: new DateTime());
    }

    public function delete(string $id): bool
    {
        return true;
    }

    public function list(
        string $filter = '',
        string $order = ''
    ): array
    {
        return [];
    }

    public function paginate(
        string $filter = '',
        string $order = '',
        int $page = 1,
        int $totalPage = 15
    ): PaginationInterface
    {
        return new PaginationInterface();
    }
}
