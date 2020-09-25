<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
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
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        $myCategories = Category::where('user_id', null)
        ->orWhere('user_id', auth()->user()->id)
        ->get();
        return response()->json(['categories' => $myCategories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $validator = validator()->make($request->all(), [
                'name' => 'required|max:100',
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }

            $category = Category::where([
                'id' => $request->id,
                'user_id' => auth()->user()->id
            ])->first();

            if (isset($category)) {
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
        //
    }
}
