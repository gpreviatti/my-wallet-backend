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
            return $this->handleException($th, "index");
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
        try {
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
        } catch (\Throwable $th) {
            return $this->handleException($th, "store");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $uuid)
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

            $return = $this->categoryRepository->updateCategory(["name" => $request->name], $uuid);
            return response()->json($return, 400);
        } catch (\Throwable $th) {
            return $this->handleException($th, "update");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $uuid
     * @return \Illuminate\Http\Response
     */
    public function delete(string $uuid)
    {
        try {
            $category = $this->categoryRepository->findByUuid($uuid);
            if ($category->user_id == auth()->user()->id) {
                if ($this->categoryRepository->delete($category->id)) {
                    return response()->json(['message' => 'Category deleted with success']);
                };
            }
            return response()->json([
                'message' => "Error to delete Category $category->name"
            ], 400);
        } catch (\Throwable $th) {
            return $this->handleException($th, "delete");
        }
    }
}
