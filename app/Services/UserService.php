<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function list($request)
    {
        $page_no        = $request->page ?? 1;
        $perPage        = $request->perPage ?? 10;
        $search         = $request->search ?? null;
        $search         = Str::squish($search);
        return User::select('id', 'name', 'email', 'created_at', 'is_active', 'profile_photo_path')
        ->when(
            $search,
            fn ($q, $search) =>
            $q->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%$search%")
        )
        ->latest()
        ->paginate($perPage);
    }

    public function create($request)
    {
        User::create($request);
    }

    public function updateInformation($id, $request)
    {
        $user = User::findOrFail($id);
        $user->update($request);
    }

    public function updatePassword($id, $request)
    {
        $user = User::findOrFail($id);
        $user->password = Hash::make($request['new_password']);
        $user->save();
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
    }
}
