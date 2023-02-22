<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    private $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function user(Request $request)
    {
        $user = $this->userService->getUser();

        $resource = new UserResource($user);

        if ($user->isInfluencer()) {
            return $resource->additional([
                'data' => [
                    'revenue' => $user->revenue,
                ]
            ]);
        }
        return $resource->additional([
            'data' => [
            //    'role' => $user->role(),
                'role' => ['role' => 'admin'],
            //    'permissions' => $user->permissions()
                    'permissions' => ['edit' => 'all']
            ],
        ]);
    }

    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $scope = $request->input('scope');
            if ($user->isInfluencer() && $scope !== 'influencer') {
                return \response([
                    'error' => 'Access denied!',
                ], Response::HTTP_FORBIDDEN);
            }
            $token = $user->createToken($scope, [$scope])->accessToken;

            $cookie = \cookie('jwt', $token, 3600);

            return \response([
                'token' => $token,
            ])->withCookie($cookie);
        }
        return response([
            'error' => 'Invalid Credentials!',
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function logout()
    {
        $cookie = \Cookie::forget('jwt');
        return \response([
            'message' => 'success',
        ])->withCookie($cookie);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create(
            $request->only('first_name', 'last_name', 'email',) + [
                'password' => Hash::make($request->input('password')),
                // 'role_id' => 1,
                'is_influencer' => 1,
            ]);
        return response($user, Response::HTTP_CREATED);
    }


    /*
        public function updateInfo(Request $request)
        {
            $user = \Auth::user();
            $user->update([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
            ]);
            return response(new UserResource($user), Response::HTTP_ACCEPTED);
        }

        public function updatePassword(Request $request)
        {
            $user = \Auth::user();
            $user->update([
                'password' => Hash::make($request->input('password')),
            ]);
            return response(new UserResource($user), Response::HTTP_ACCEPTED);
        }*/
}
