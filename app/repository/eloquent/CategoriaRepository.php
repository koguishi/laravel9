<?php

namespace app\repository\eloquent;

use App\Models\Categoria as CategoriaModel;
use App\repository\PaginationPresenter;
use core\domain\entity\Categoria as CategoriaEntity;
use core\domain\entity\Entity;
use core\domain\exception\NotFoundException;
use core\domain\repository\CategoriaRepositoryInterface;
use core\domain\repository\PaginationInterface;

class CategoriaRepository implements CategoriaRepositoryInterface
{
    protected $model;

    public function __construct(CategoriaModel $model) {
        $this->model = $model;
    }

    private function toEntity(object $object): CategoriaEntity {
        $entity = new CategoriaEntity(
            id: $object->id,
            nome: $object->nome,
            descricao: $object->descricao,
        );
        ((bool) $object->ativo) ? $entity->ativar() : $entity->desativar();

        return $entity;
    }

    public function create(Entity $entity): CategoriaEntity
    {
        $categoriaDb = $this->model->create([
            'id' => $entity->id(),
            'nome' => $entity->nome,
            'descricao' => $entity->descricao,
            'ativo' => $entity->ativo,
        ]);
        return $this->toEntity($categoriaDb);
    }
    
    public function read(string $id): CategoriaEntity
    {
        $categoriaDb = $this->model->find($id);
        if (! $categoriaDb) {
            throw new NotFoundException('Categoria not found');
        }
        return $this->toEntity($categoriaDb);
    }

    public function update(Entity $entity): CategoriaEntity
    {
        $categoriaDb = $this->model->find($entity->id());
        if (! $categoriaDb) {
            throw new NotFoundException('Categoria not found');
        }        

        $categoriaDb->update([
            'nome' => $entity->nome,
            'descricao' => $entity->descricao,
            'ativo' => $entity->ativo,
        ]);

        $categoriaDb->refresh();

        return $this->toEntity($categoriaDb);
    }

    public function delete(string $id): bool
    {
        $categoriaDb = $this->model->find($id);
        if (! $categoriaDb) {
            throw new NotFoundException('Categoria not found');
        }
        return $categoriaDb->delete();
    }

    public function getIds(array $categoriasIds = []): array
    {
        return $this->model
            ->whereIn('id', $categoriasIds)
            ->pluck('id')
            ->toArray();
    }

    public function list(
        string $filter = '',
        string $order = ''
    ): array
    {
        $builder = $this->model->where(
            function ($query) use ($filter) {
                if ($filter) {
                    $query->where('nome', 'LIKE', "%{$filter}%");
                    $query->orWhere('descricao', 'LIKE', "%{$filter}%");
                }
            }
        );

        if (!empty($order)) {
            $arrOrder = json_decode($order, true);
            foreach ($arrOrder as $column => $direction) {
                $builder->orderBy($column, $direction);
            }
        }

        $categorias = $builder->get();
        return $categorias->toArray();
    }

    public function paginate(
        string $filter = '',
        string $order = '',
        int $page = 1,
        int $totalPage = 15
    ): PaginationInterface
    {
        $query = $this->model;
        if ($filter) {
            $query = $query->where('nome', 'LIKE', "%{$filter}%");
        }

        if (!empty($order)) {
            $arrOrder = json_decode($order);
            foreach ($arrOrder as $column => $direction) {
                $query = $query->orderBy($column, $direction);
            }
        }

        $paginator = $query->paginate(
            page: $page,
            perPage: $totalPage,
        );

        return new PaginationPresenter($paginator);
    }    
}
