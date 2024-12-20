<?php

namespace app\repository\eloquent;

use App\Models\Atleta as AtletaModel;
use app\repository\PaginationPresenter;
use core\domain\entity\Atleta;
use core\domain\entity\Entity;
use core\domain\exception\AlreadyExistsException;
use core\domain\exception\NotFoundException;
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
            dtNascimento: new DateTime($object->dt_nascimento),
        );

        return $entity;
    }    

    public function create(Entity $entity): Atleta
    {
        $arrAtleta = $this->model->where('nome', '=', $entity->nome)->get()->toArray();
        if (! empty($arrAtleta)) {
            throw new AlreadyExistsException('Atleta already exists');
        }

        $atletaDb = $this->model->create([
            'id' => $entity->id(),
            'nome' => $entity->nome,
            'dt_nascimento' => $entity->dtNascimento->format('Y-m-d'),
        ]);
        return $this->toEntity($atletaDb);
    }
    
    public function read(string $id): Atleta
    {
        $atletaDb = $this->model->find($id);
        if (! $atletaDb) {
            throw new NotFoundException('Atleta not found');
        }
        return $this->toEntity($atletaDb);
    }

    public function update(Entity $entity): Atleta
    {
        $atletaDb = $this->model->find($entity->id());
        if (! $atletaDb) {
            throw new NotFoundException('Atleta not found');
        }

        $atletaDb->update([
            'nome' => $entity->nome,
            'dt_nascimento' => $entity->dtNascimento,
        ]);

        $atletaDb->refresh();

        return $this->toEntity($atletaDb);        
    }

    public function delete(string $id): bool
    {
        $atletaDb = $this->model->find($id);
        if (! $atletaDb) {
            throw new NotFoundException('Atleta not found');
        }        
        return $atletaDb->delete();
    }

    public function getIds(array $atletasIds = []): array
    {
        return $this->model
            ->whereIn('id', $atletasIds)
            ->pluck('id')
            ->toArray();
    }

    public function list(
        string $order = '',
        string $filter_nome = '',
        ?DateTime $filter_dtNascimento_inicial = null,
        ?DateTime $filter_dtNascimento_final = null,
    ): array
    {
        $query = $this->model;

        if ($filter_nome) {
            $query = $query->where('nome', 'LIKE', "%{$filter_nome}%");
        }

        if ($filter_dtNascimento_inicial) {
            $query = $query->whereDate('dt_nascimento', '>=', $filter_dtNascimento_inicial->format('Y-m-d'));
        }

        if ($filter_dtNascimento_final) {
            $query = $query->whereDate('dt_nascimento', '<=', $filter_dtNascimento_final->format('Y-m-d'));
        }

        if (!empty($order)) {
            $arrOrder = json_decode($order);
            foreach ($arrOrder as $column => $direction) {
                $query = $query->orderBy($column, $direction);
            }
        }

        return $query->get()->toArray();
    }

    public function paginate(
        int $page = 1,
        int $perPage = 15,
        ?string $order = '',
        ?string $filter_nome = '',
        ?DateTime $filter_dtNascimento_inicial = null,
        ?DateTime $filter_dtNascimento_final = null,
    ): PaginationInterface
    {
        $query = $this->model;

        if ($filter_nome) {
            $query = $query->where('nome', 'LIKE', "%{$filter_nome}%");
        }

        if ($filter_dtNascimento_inicial) {
            $query = $query->whereDate('dt_nascimento', '>=', $filter_dtNascimento_inicial->format('Y-m-d'));
        }

        if ($filter_dtNascimento_final) {
            $query = $query->whereDate('dt_nascimento', '<=', $filter_dtNascimento_final->format('Y-m-d'));
        }

        if (!empty($order)) {
            $arrOrder = json_decode($order);
            foreach ($arrOrder as $column => $direction) {
                $query = $query->orderBy($column, $direction);
            }
        }

        $paginator = $query->paginate(
            page: $page,
            perPage: $perPage,
        );

        return new PaginationPresenter($paginator);
    }
}
