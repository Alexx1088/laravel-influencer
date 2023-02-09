<?php

namespace App\Http\Controllers\Admin;

use App\Events\AdminAddedEvent;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Jobs\AdminAdded;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    public function index()
    {
        \Gate::authorize('view', 'users');
        $users = User::with('role')->paginate();
        return UserResource::collection($users);
    }

    public function show($id)
    {
        \Gate::authorize('view', 'users');
        $user = User::with('role')->find($id);
        return new UserResource($user);
    }

    public function store(UserCreateRequest $request)
    {
        \Gate::authorize('edit', 'users');
        $user = User::create(
            $request->only('first_name', 'last_name', 'email') + [
                'password' => Hash::make(1234),
            ]);

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->input('role_id')
        ]);

     //   event(new AdminAddedEvent($user));
        AdminAdded::dispatch($user->email);
        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        \Gate::authorize('edit', 'users');
        $user = User::find($id);

        $user->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
        ]);

        UserRole::where('user_id', $user->id)->delete();

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->input('role_id')
        ]);
        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        \Gate::authorize('edit', 'users');
        User::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
