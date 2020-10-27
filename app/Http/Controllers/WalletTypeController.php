<?php

namespace App\Http\Controllers;

use App\Models\Repositories\WalletTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WalletTypeController extends Controller
{
    /**
     * repository variable
     *
     * @var WalletTypeRepository
     */
    private $repository;

    public function __construct()
    {
        // set repository
        $this->repository = new WalletTypeRepository;
    }


    /**
     * Display a listing of the resource.
    *
    * @param string $uuid
    * @return JsonResponse
    */
    public function index(string $uuid = null) : JsonResponse
    {
        try {
            if ($uuid) {
                return response()->json($this->repository->findByUuid($uuid));
            }
            return response()->json($this->repository->all());
        } catch (\Throwable $th) {
            $this->handleException($th, "index");
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
                'name' => 'required|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }

            return response()->json($this->repository->createWalletType($request->all()));
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
                'name' => 'max:50'
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
     * Remove the specified resource from storage.
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
