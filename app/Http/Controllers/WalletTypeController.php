<?php

namespace App\Http\Controllers;

use App\Models\WalletType;
use Illuminate\Http\Request;

class WalletTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(WalletType::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->id == 1) {
            # code...
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WalletType  $walletType
     * @return \Illuminate\Http\Response
     */
    public function show(WalletType $walletType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WalletType  $walletType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WalletType $walletType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WalletType  $walletType
     * @return \Illuminate\Http\Response
     */
    public function destroy(WalletType $walletType)
    {
        //
    }
}
