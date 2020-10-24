<?php

namespace App\Models\Repositories;

use App\Models\Entities\Category;

class CategoryRepository extends Repository
{
    /**
     * Constructor to bind model to repo
     */
    public function __construct()
    {
        parent::__construct(new Category);
    }

    /**
     * Return the common and user categories
     *
     * @return Category
     */
    public function getUserCategories() : Category
    {
        return $this->model->where([
            'user_id' => auth()->user()->id
        ])
        ->orWhere('user_id', null)
        ->get();
    }

    /**
     * Update the category releted to logged user
     *
     * @param array $data
     * @param string $uuid
     * @return void
     */
    public function updateUserCategory(array $data, string $uuid) : array
    {
        $category = $this->findByUuid($uuid);
        if (isset($category) && $category->user_id == auth()->user()->id) {
            if ($this->updateByUuid($data, $uuid)) {
                return [
                    "success" => true,
                    "message" => "Category updated with success",
                    "data" => $category
                ];
            };
        }
        return ["success" => false, "message" => "Error to update category"];
    }

    /**
     * Delete resource by uuid
     *
     * @param string $uuid
     * @return void
     */
    public function deleteByUUid(string $uuid) : array
    {
        $category = $this->findByUuid($uuid);
        if ($category->user_id == auth()->user()->id) {
            if ($this->repository->delete($category->id)) {
                return ["success" => true, "message" => "Category deleted with success"];
            };
        }
        return ["success" => false, "message" => "Error to delete category"];
    }
}
