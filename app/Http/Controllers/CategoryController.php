<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * List all categories available and custom for logged User
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $myCategories = Category::where('user_id', null)
        ->orWhere('user_id', auth()->user()->id)
        ->get();
        return response()->json($myCategories);
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

        $newCategory = Category::firstOrCreate([
            'name' => $request->name,
            'user_id' => auth()->user()->id
        ]);

        if ($newCategory) {
            $newCategory = Category::firstOrCreate([
                'name' => $request->name,
                'user_id' => auth()->user()->id
            ]);
        }

        return response()->json([
            'message' => 'Successfully registered',
            'new_category' => $newCategory
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return response()->json($category);
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
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Erro to update Category"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            if ($category->user_id == auth()->user()->id) {
                if ($category->destroy($category->id)) {
                    return response()->json(['message' => 'Category deleted with success']);
                };
            }
            return response()->json([
                'message' => "Error to delete Category $category->name"
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Erro to update Category"]);
        }
    }
}
