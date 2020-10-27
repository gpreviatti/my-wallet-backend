<?php

namespace App\Http\Controllers;

use App\Models\Repositories\CategoryRepository;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * repository variable
     *
     * @var CategoryRepository
     */
    private $repository;

    public function __construct()
    {
        // set repository
        $this->repository = new CategoryRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @param string $uuid
     * @return Response
     */
    public function index(string $uuid = null)
    {
        try {
            if ($uuid) {
                return response()->json($this->repository->findByUuid($uuid));
            }
            return response()->json($this->repository->getUserCategories());
        } catch (\Throwable $th) {
            return $this->handleException($th, "index");
        }
    }

    /**
     * Create new resource.
     *
     * @param  Request $request
     * @return Response
     */
    public function create(Request $request)
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

            $newCategory = $this->repository->create([
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
     * @param  Request $request
     * @param string $uuid
     * @return Response
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

            $return = $this->repository->updateUserCategory(["name" => $request->name], $uuid);
            return response()->json($return, 400);
        } catch (\Throwable $th) {
            return $this->handleException($th, "update");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $uuid
     * @return Response
     */
    public function delete(string $uuid)
    {
        try {
            return response()->json($this->repository->deleteByUUid($uuid), 200);
        } catch (\Throwable $th) {
            return $this->handleException($th, "delete");
        }
    }
}
