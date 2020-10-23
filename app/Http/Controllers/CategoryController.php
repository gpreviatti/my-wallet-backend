<?php

namespace App\Http\Controllers;

use App\Models\Repositories\CategoryRepository;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * repository
     *
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct()
    {
        // set repository
        $this->categoryRepository = new CategoryRepository();
    }
    /**
     * Display a listing of the resource.
     *
     * @param string $uuid
     * @return \Illuminate\Http\Response
     */
    public function index(string $uuid = null)
    {
        try {
            if ($uuid) {
                return response()->json($this->categoryRepository->findByUuid($uuid));
            }
            return response()->json($this->categoryRepository->getUserCategories());
        } catch (\Throwable $th) {
            logger()->error($th->getMessage());
            return response()->json($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'name' => 'required||max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $newCategory = $this->categoryRepository->create([
            'name' => $request->name,
            'uuid' => Str::uuid(),
            'user_id' => auth()->user()->id
        ]);

        if ($newCategory) {
            return response()->json([
                'message' => 'Successfully registered',
                'new_category' => $newCategory
            ], 201);
        }

        return response()->json('Error to create category', 400);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        try {
            $validator = validator()->make(
                $request->all(),
                ['name' => 'required|max:100']
            );

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }

            if (isset($category) && $category->user_id == auth()->user()->id) {
                if ($category->update(['name' => $request->name])) {
                    return response()->json([
                        'message' => 'Category updated with success'
                    ]);
                };
            }
            return response()->json([
                'message' => "Error to update Category $category->name"
            ], 400);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Erro to update Category"
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function delete(Category $category)
    {
        try {
            if ($category->user_id == auth()->user()->id) {
                if ($this->categoryRepository->delete($category->id)) {
                    return response()->json(['message' => 'Category deleted with success']);
                };
            }
            return response()->json([
                'message' => "Error to delete Category $category->name"
            ], 400);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Erro to update Category"], 400);
        }
    }
}
