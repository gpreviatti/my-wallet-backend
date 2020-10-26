<?php

namespace App\Http\Controllers;

use App\Models\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * repository variable
     *
     * @var UserRepository
     */
    private $repository;

    public function __construct()
    {
        // set repository
        $this->repository = new UserRepository;
    }

    /**
     * Update your own user
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request) : JsonResponse
    {
        try {
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
            if ($this->repository->update($request->all(), auth()->user()->id)) {
                return response()->json([
                    "success" => true,
                    "message" => "User updated with success"
                ]);
            }
            return response()->json([
                "success" => false,
                "message" => "Fail to update user"
            ], 400);
        } catch (\Throwable $th) {
            return $this->handleException($th, "update");
        }
    }

    /**
     * Delete user own user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete() : JsonResponse
    {
        try {
            if ($this->repository->delete(auth()->user()->id)) {
                return response()->json([
                    "success" => true,
                    "message" => "Your user has been deleted successfully"
                ]);
            }
            return response()->json([
                "success" => false,
                "message" => "Fail to remove your user"
            ]);
        } catch (\Throwable $th) {
            return $this->handleException($th, "delete");
        }
    }
}
