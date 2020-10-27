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
     * @return bool
     */
    public function update(array $data, int $id) : bool
    {
        $record = $this->show($id);
        return $record->update($data);
    }

    /**
     * update record in the database by uuid
     *
     * @param array $data
     * @param string $uuid
     * @return array
     */
    public function updateByUuid(array $data, string $uuid) : array
    {
        $record = $this->findByUuid($uuid);
        if ($record->update($data)) {
            return [
                "success" => true,
                "message" => "Updated with success",
                "data" => $data
            ];
        }
        return ["success" => false, "message" => "Fail to update"];
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
     * Delete resource by uuid
     *
     * @param string $uuid
     * @return void
     */
    public function deleteByUUid(string $uuid) : array
    {
        $model = $this->findByUuid($uuid);
        if ($this->delete($model->id)) {
            return ["success" => true, "message" => "Deleted with success"];
        };
        return ["success" => false, "message" => "Error to delete"];
    }

    /**
     * show the record with the given id
     *
     * @param int $id
     * @return
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
