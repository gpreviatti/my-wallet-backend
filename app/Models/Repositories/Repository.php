<?php

namespace App\Models\Repositories;

use Illuminate\Database\Eloquent\Model;

class Repository implements RepositoryInterface
{
    /**
     * model property on class instances
     *
     * @var Model
     */
    protected $model;

    /**
     * Constructor to bind model to repo
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all instances of model
     *
     * @return Model
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * create a new record in the database
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * update record in the database
     *
     * @param array $data
     * @param int $id
     * @return void
     */
    public function update(array $data, int $id)
    {
        $record = $this->find($id);
        return $record->update($data);
    }

    /**
     * update record in the database by uuid
     *
     * @param array $data
     * @param string $uuid
     * @return void
     */
    public function updateByUuid(array $data, string $uuid)
    {
        $record = $this->findByUuid($uuid);
        return $record->update($data);
    }

    /**
     * remove record from the database
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id)
    {
        return $this->model->destroy($id);
    }

    /**
     * show the record with the given id
     *
     * @param int $id
     * @return Model
     */
    public function show(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Return a specific resource by uuid
     *
     * @param string $uuid
     * @return Model
     */
    public function findByUuid(string $uuid)
    {
        return $this->model->where('uuid', $uuid)->first();
    }

    /**
     * Get the associated model
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set the associated model
     *
     * @param Model $model
     * @return Model
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Eager load database relationships
     *
     * @param array $relations
     * @return Model
     */
    public function with(array $relations)
    {
        return $this->model->with($relations);
    }
}
