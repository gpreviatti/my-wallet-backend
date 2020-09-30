<?php

namespace App\Http\Controllers;

use App\Models\UsersHaveWallets;
use App\Models\Wallet;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $wallets = Wallet::with('users')->get();
    //     return response()->json($wallets);
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'wallets_types_id' => 'required|integer|exists:wallet_types,id',
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
     * @param  \App\Models\Wallet $wallet
     * @return \Illuminate\Http\Response
     */
    public function show(Wallet $wallet)
    {
        $userWallet = UsersHaveWallets::where([
            'user_id' => auth()->user()->id,
            'wallet_id' => $wallet->id
        ])->first();
        if ($userWallet) {
            return response()->json(
                $wallet->where('id', $wallet->id)->with('type')->first()
            );
        }
        return response()->json('Wallet not found', 400);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Wallet $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wallet $wallet)
    {
        $validator = validator()->make($request->all(), [
            'wallets_types_id' => 'required|integer|exists:wallet_types,id',
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

        if ($wallet->update($request->all())) {
            return response()->json($wallet);
        }
        return response()->json('Error to update wallet');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallet $wallet)
    {
        if ($wallet->destroy($wallet->id)) {
            return response()->json('Wallet was successfully destroyed');
        }
        return response()->json('Error to destroy wallet');
    }
}
