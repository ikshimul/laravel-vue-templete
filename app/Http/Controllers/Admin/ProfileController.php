<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\PasswordUpdateRequest;
use App\Http\Requests\Admin\Profile\ProfileRequest;
use Illuminate\Http\Request;
use App\Services\ProfileService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile.index');
    }

    public function profileInformation($id = null)
    {
        $user_id = $id ?? Auth::user()->id;
        return (new ProfileService())->getInformation($user_id);
    }

    public function profileInformationUpdate(ProfileRequest $request)
    {
        try {
            (new ProfileService())->updateInformation($request->validated());
            return response()->json(['success' => true, 'message' => 'Profile updated successfully.']);
        } catch(Exception $ex) {
            return response()->json(['success' => false, 'message' => $ex]);
        }
    }

    public function passwordUpdate(PasswordUpdateRequest $request)
    {
        try {
            $user = Auth::user();
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['errors' => ['current_password' => ['Current password does not match.']]], 422);
            }
            (new ProfileService())->updatePassword($request->validated());
            return response()->json(['success' => true, 'message' => 'Password updated successfully.']);
        } catch(Exception $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()]);
        }
    }
}
