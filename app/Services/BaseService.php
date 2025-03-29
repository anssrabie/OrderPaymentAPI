<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;

abstract class BaseService
{
    public function __construct(public BaseRepository $repository)
    {
    }

    public function getData(array $relations = [], bool $usePagination = false,int $perPage = 30, array $filters = [], array $scopes = [])
    {
        $query = $this->repository->withRelations($relations)->withScopes($scopes);
        if (!empty($filters)) {
            $query = $query->filter($filters);
        }
        return $usePagination ? $query->paginate($perPage) : $query->all();
    }


    public function storeResource(array $data)
    {
        return $this->repository->create($data);
    }

    public function showResource($id, array $relations = [])
    {
        return $this->repository->withRelations($relations)->find($id);
    }

    public function updateResource($id, $data)
    {
        return $this->repository->update($data, $id);
    }

    public function deleteResource($id): void
    {
         $this->repository->delete($id);
    }

    public function getBy(array $conditions): Collection
    {
        return $this->repository->getBy($conditions);
    }
}
