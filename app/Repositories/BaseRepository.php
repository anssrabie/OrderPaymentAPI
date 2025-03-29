<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\{Builder, Collection, ModelNotFoundException};
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

class BaseRepository
{
    protected array $relations = [];
    protected array $scopes = [];
    protected $modelClass;
    protected $modelName;
    public function __construct(public $model)
    {
        $this->modelClass = get_class($this->model);
        $this->modelName = class_basename($this->model);
    }

    public function query(): QueryBuilder
    {
        return QueryBuilder::for($this->modelClass)->scopes($this->scopes)->with($this->relations);
    }

    public function withRelations($relations): static
    {
        $this->relations = $relations;
        return $this;
    }

    public function withScopes(array $scopes): static
    {
        $this->scopes = $scopes;
        return $this;
    }

    public function all(): Collection
    {
        return $this->query()->latest()->get();
    }

    public function paginate($perPage = 10): LengthAwarePaginator
    {
        return $this->query()->latest()->paginate($perPage);
    }

    public function filter(array $filters = []): QueryBuilder
    {
        return $this->query()->allowedFilters($filters);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function find($id)
    {
        try {
            return $this->query()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception("$this->modelName not found.", 404);
        }
    }

    public function update(array $data, $id)
    {
        $modelObject = $this->find($id);
        $modelObject->update($data);
        $modelObject->refresh();
        return $modelObject;
    }

    public function delete($id)
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function getBy(array $conditions): Collection
    {
        return $this->query()->where($conditions)->get();
    }

    public function findBy(array $conditions)
    {
        return $this->query()->where($conditions)->firstOrFail();
    }

    public function whereIn(string $column, array $values): QueryBuilder
    {
        return $this->query()->whereIn($column, $values);
    }

    public function deleteBy(array $conditions){
        return $this->query()->where($conditions)->delete();
    }

    public function updateOrCreate(array $conditions, array $data)
    {
        return $this->model->updateOrCreate($conditions, $data);
    }
}
