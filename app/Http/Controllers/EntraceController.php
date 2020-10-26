<?php

namespace App\Http\Controllers;

use App\Models\Repositories\EntraceRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntraceController extends Controller
{
    /**
     * repository variable
     *
     * @var EntraceRepository
     */
    private $repository;

    public function __construct()
    {
        // set repository
        $this->repository = new EntraceRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(string $uuid) : JsonResponse
    {
        try {
            if ($uuid) {
                return response()->json($this->repository->findByUuid($uuid));
            }
        } catch (\Throwable $th) {
            $this->handleException($th, "index");
        }
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
                'wallet_uuid' => 'required|string|exists:wallets,uuid',
                'category_uuid' => 'required|string|exists:categories,uuid',
                'ticker' => 'max:15',
                'type' => 'max:20',
                'description' => 'string|required|max:255',
                'observation' => 'string|max:255',
                'value' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }

            return response()->json($this->repository->createEntrace($request->all()));
        } catch (\Throwable $th) {
            $this->handleException($th, "create");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string $entraceUuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $entraceUuid) : JsonResponse
    {
        $validator = validator()->make($request->all(), [
            'wallet_id' => 'required|string|exists:wallets,id',
            'category_id' => 'required|string|exists:categories,id',
            'ticker' => 'max:15',
            'type' => 'max:20',
            'description' => 'string|max:255',
            'observation' => 'string|max:255',
            'value' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $entrace = Entrace::where('uuid', $entraceUuid)->first();
        if (!$entrace) {
            return response()->json('Entrace not found', 400);
        }

        DB::beginTransaction();
        $newValue = isset($request->value) ?? 0;
        /** validate if value if different then update in wallet table */
        if ($newValue && $entrace->value != $newValue) {
            if ($request->wallet_id && $request->category_id) {
                $walletUpdate = $this->entraceUpdateValues($request->wallet_id, $request->category_id, $newValue);
                if ($walletUpdate) {
                    $entrace->update($request->all());
                    DB::commit();
                    return response()->json($entrace);
                }
            }
        }
        return response()->json('Error to update entrace');
    }

    /**
     * Delete the specified resource from storage.
     *
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $uuid) : JsonResponse
    {
        try {
            return response()->json($this->repository->deleteByUUid($uuid));
        } catch (\Throwable $th) {
            $this->handleException($th, "delete");
        }
    }
}
