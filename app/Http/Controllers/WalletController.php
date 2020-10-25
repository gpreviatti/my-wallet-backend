<?php

namespace App\Http\Controllers;

use App\Models\Repositories\WalletRepository;
use App\Models\Repositories\UsersHaveWalletsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * repository variable
     *
     * @var WalletRepository
     */
    private $repository;

    public function __construct()
    {
        // set repository
        $this->repository = new WalletRepository;
    }

    /**
     * Display the specified resource.
     *
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(string $uuid) : JsonResponse
    {
        return response()->json($this->repository->findByUuid($uuid));
    }

    /**
     * Create new resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request) : JsonResponse
    {
        try {
            $validator = validator()->make($request->all(), [
                'wallet_types_id' => 'required|integer|exists:wallet_types,id',
                'name' => 'required|max:50',
                'description' => 'max:255',
                'current_value' => 'required|numeric',
                'due_date' => 'integer',
                'close_date' => 'integer',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }

            return response()->json($this->repository->create($request->all()));
        } catch (\Throwable $th) {
            $this->handleException($th, "store");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $uuid) : JsonResponse
    {
        try {
            $validator = validator()->make($request->all(), [
                'wallet_types_id' => 'required|integer|exists:wallet_types,id',
                'name' => 'max:50',
                'description' => 'max:255',
                'current_value' => 'numeric',
                'due_date' => 'integer',
                'close_date' => 'integer',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }

            return response()->json($this->repository->updateByUuid($request->all(), $uuid));
        } catch (\Throwable $th) {
            $this->handleException($th, "update");
        }
    }

    /**
     * Delete resource.
     *
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $uuid) : JsonResponse
    {
        try {
            return response()->json($this->repository->deleteByUUid($uuid), 200);
        } catch (\Throwable $th) {
            $this->handleException($th, "delete");
        }
    }
}
