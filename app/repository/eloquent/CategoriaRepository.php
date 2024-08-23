<?php

namespace app\repository\eloquent;

use App\Models\Categoria as CategoriaModel;
use core\domain\entity\Categoria as CategoriaEntity;
use core\domain\repository\CategoriaRepositoryInterface;
use core\domain\repository\PaginationInterface;

class CategoriaRepository implements CategoriaRepositoryInterface
{
    protected $model;

    public function __construct(CategoriaModel $model) {
        $this->model = $model;
    }

    public function create(CategoriaEntity $categoria): CategoriaEntity
    {
        $this->model->create([
            'id' => $categoria->id(),
            'nome' => $categoria->nome,
            'descricao' => $categoria->descricao,
            'ativo' => $categoria->ativo,
        ]);

        return $categoria;
    }
    
    public function read(string $id): CategoriaEntity
    {
        return new CategoriaEntity();
    }

    public function update(CategoriaEntity $categoria): CategoriaEntity
    {
        return $categoria;
    }

    public function delete(string $id): bool
    {
        return true;
    }

    public function readAll(string $filter = '', string $order = 'DESC'): array
    {
        return [];
    }

    public function paginate(
        string $filter = '',
        string $order = 'DESC',
        int $page = 1,
        int $totalPage = 15
    ): PaginationInterface
    {
        return new PaginationInterface();
    }    
}
