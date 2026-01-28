<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    /**
     * Get all records.
     */
    public function all(): Collection;

    /**
     * Find a record by ID.
     */
    public function find(int $id): ?Model;

    /**
     * Find a record by ID or throw exception.
     */
    public function findOrFail(int $id): Model;

    /**
     * Create a new record.
     */
    public function create(array $data): Model;

    /**
     * Update a record.
     */
    public function update(Model $model, array $data): Model;

    /**
     * Delete a record.
     */
    public function delete(Model $model): bool;

    /**
     * Get paginated records.
     */
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    /**
     * Get query builder.
     */
    public function query(): Builder;

    /**
     * Find records by field value.
     */
    public function findBy(string $field, mixed $value): ?Model;

    /**
     * Find multiple records by field value.
     */
    public function findWhere(string $field, mixed $value): Collection;

    /**
     * Get records with relationships.
     */
    public function with(array $relations): self;
}
