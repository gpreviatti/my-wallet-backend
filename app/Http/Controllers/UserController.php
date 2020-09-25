<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        auth()->user()->id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        if (User::destroy(auth()->user()->id)) {
            return response()->json(['message' => 'User deleted with success! :(']);
        };
        return response()->json(['message' => 'Fail to remove User :)']);
    }
}
