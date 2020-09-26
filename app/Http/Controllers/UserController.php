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
    public function update(Request $request, User $user)
    {
        $validator = validator()->make($request->all(), [
            'name' => 'required|between:2,100',
            'email' => 'email|unique:users|max:50',
            'password' => 'confirmed|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        try {
            if (auth()->user()->id == $user->id) {
                $user->update($request->all());
                return response()->json(['message' => 'User updated with success']);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Fail to update user'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->id == auth()->user()->id) {
            $user->destroy($user->id);
            return response()->json(['message' => 'User deleted with success! :(']);
        }
        return response()->json(['message' => 'Fail to remove User :)']);
    }
}
