<?php

namespace App\Http\Controllers;

use App\Models\UsersHaveWallets;
use App\Models\Wallet;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) : JsonResponse
    {
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

        $wallet = Wallet::create(array_merge(
            $request->all(),
            ['uuid' => Str::uuid()]
        ));

        if ($wallet) {
            $usersHaveWallets = UsersHaveWallets::create([
                'wallet_id' => $wallet->id,
                'user_id' => auth()->user()->id
            ]);

            if ($usersHaveWallets) {
                return response()->json($wallet);
            }
        }
        return response()->json('Error to create wallet');
    }

    /**
     * Display the specified resource.
     *
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $uuid) : JsonResponse
    {
        $userWallet = UsersHaveWallets::where([
            'user_id' => auth()->user()->id,
            'uuid' => $uuid
        ])->first();
        if ($userWallet) {
            return response()->json($userWallet->with('type')->first());
        }
        return response()->json('Wallet not found', 400);
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

        $wallet = Wallet::where('uuid', $uuid)->first();
        if ($wallet->update($request->all())) {
            return response()->json($wallet);
        }
        return response()->json('Error to update wallet');
    }

    /**
     * Delete the specified resource from storage.
     *
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $uuid) : JsonResponse
    {
        $wallet = Wallet::where('uuid', $uuid)->first();
        if ($wallet->delete()) {
            return response()->json('Wallet was successfully deleted');
        }
        return response()->json('Error to delete wallet');
    }
}
