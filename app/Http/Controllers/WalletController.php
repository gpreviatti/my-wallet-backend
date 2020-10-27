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
     * @return JsonResponse
     */
    public function index(string $uuid) : JsonResponse
    {
        return response()->json($this->repository->findByUuid($uuid));
    }

    /**
     * Return all entraces of a specific wallet
     *
     * @param string $uuid
     * @return JsonResponse
     */
    public function entraces(string $uuid)
    {
        try {
            return response()->json($this->repository->entraces($uuid));
        } catch (\Throwable $th) {
            return $this->handleException($th, "entraces");
        }
    }

    /**
     * Create new resource.
     *
     * @param  Request $request
     * @return JsonResponse
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

            return response()->json($this->repository->createUserWallet($request->all()));
        } catch (\Throwable $th) {
            return $this->handleException($th, "store");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param string $uuid
     * @return JsonResponse
     */
    public function update(Request $request, string $uuid) : JsonResponse
    {
        try {
            $validator = validator()->make($request->all(), [
                'wallet_types_id' => 'integer|exists:wallet_types,id',
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
            return $this->handleException($th, "update");
        }
    }

    /**
     * Delete resource.
     *
     * @param string $uuid
     * @return JsonResponse
     */
    public function delete(string $uuid) : JsonResponse
    {
        try {
            return response()->json($this->repository->deleteByUUid($uuid));
        } catch (\Throwable $th) {
            return $this->handleException($th, "delete");
        }
    }
}
