<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Update your own user
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request) : JsonResponse
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
            $user = User::find(auth()->user()->id)->first();
            if ($user) {
                $user->update($request->all());
                return response()->json(['message' => 'User updated with success']);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Fail to update user'], 400);
        }
    }

    /**
     * Delete user own user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete() : JsonResponse
    {
        if (User::find(auth()->user()->id)->delete()) {
            return response()->json(['message' => 'Your user has been deleted successfully']);
        }
        return response()->json(['message' => 'Fail to remove your user']);
    }
}
