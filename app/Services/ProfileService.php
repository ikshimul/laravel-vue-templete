<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    public function getInformation($id)
    {
        return User::select('id', 'name', 'email')->where('id', $id)->first();
    }

    public function updateInformation($request)
    {
        $user = Auth::user();
        $user->update($request);
    }

    public function updatePassword($request)
    {
        $user = Auth::user();
        $user->password = Hash::make($request['new_password']);
        $user->save();
    }
}
