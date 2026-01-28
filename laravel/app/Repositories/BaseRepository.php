<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;
    protected array $with = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records.
     */
    public function all(): Collection
    {
        return $this->query()->get();
    }

    /**
     * Find a record by ID.
     */
    public function find(int $id): ?Model
    {
        return $this->query()->find($id);
    }

    /**
     * Find a record by ID or throw exception.
     */
    public function findOrFail(int $id): Model
    {
        return $this->query()->findOrFail($id);
    }

    /**
     * Create a new record.
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update a record.
     */
    public function update(Model $model, array $data): Model
    {
        $model->update($data);
        return $model->fresh();
    }

    /**
     * Delete a record.
     */
    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    /**
     * Get paginated records.
     */
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->applyFilters($this->query(), $filters);
        return $query->latest()->paginate($perPage);
    }

    /**
     * Get query builder.
     */
    public function query(): Builder
    {
        $query = $this->model->newQuery();

        if (!empty($this->with)) {
            $query->with($this->with);
        }

        return $query;
    }

    /**
     * Find a record by field value.
     */
    public function findBy(string $field, mixed $value): ?Model
    {
        return $this->query()->where($field, $value)->first();
    }

    /**
     * Find multiple records by field value.
     */
    public function findWhere(string $field, mixed $value): Collection
    {
        return $this->query()->where($field, $value)->get();
    }

    /**
     * Set relationships to eager load.
     */
    public function with(array $relations): self
    {
        $this->with = $relations;
        return $this;
    }

    /**
     * Apply filters to query (to be overridden in child classes).
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        return $query;
    }

    /**
     * Get the model instance.
     */
    protected function getModel(): Model
    {
        return $this->model;
    }
}
