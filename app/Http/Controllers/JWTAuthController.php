<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Repositories\UserRepository;

class JWTAuthController extends Controller
{
    /**
     * repository variable
     *
     * @var USerRepository
     */
    private $repository;

    public function __construct()
    {
        // set repository
        $this->repository = new UserRepository;
    }

    /**
     * Register a User.
     *
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'name' => 'required|between:2,100',
            'email' => 'required|email|unique:users|max:50',
            'password' => 'required|confirmed|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $user = $this->repository->createWithUuid($request->all());

        return response()->json([
            "success" => true,
            "message" => "Successfully registered",
            "user" => $user
        ], 201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function profile()
    {
        try {
            return response()->json($this->repository->profile());
        } catch (\Throwable $th) {
            return $this->handleException($th, "profile");
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        try {
            auth()->logout();
            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Throwable $th) {
            return $this->handleException($th, "logout");
        }
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        try {
            return $this->createNewToken(auth()->refresh());
        } catch (\Throwable $th) {
            return $this->handleException($th, "refresh");
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function createNewToken($token)
    {
        try {
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]);
        } catch (\Throwable $th) {
            return $this->handleException($th, "createNewToken");
        }
    }
}
