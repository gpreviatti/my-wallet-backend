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
    public function getUserCategories()
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
    public function updateCategory(array $data, string $uuid)
    {
        $category = $this->findByUuid($uuid);
        if (isset($category) && $category->user_id == auth()->user()->id) {
            if ($this->updateByUuid($data, $uuid)) {
                return ["success" => false, "message" => "Category updated with success"];
            };
        }
        return ["success" => false, "message" => "Error to update category"];
    }
}
