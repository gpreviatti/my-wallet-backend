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
}
