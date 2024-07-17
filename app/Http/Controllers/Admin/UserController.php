<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\CreateRequest;
use App\Http\Requests\Admin\User\InformationUpdateRequest;
use App\Http\Requests\Admin\User\PasswordUpdateRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Traits\ApiTrait;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiTrait;

    public function __construct(private UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        // $users = User::factory()->count(1000)->create();
        return view('admin.user.index');
    }

    public function List(Request $request)
    {
        $data = $this->userService->list($request);
        $response = [
         'total'        => $data->total(),
         'per_page'     => $data->perPage(),
         'current_page' => $data->currentPage(),
         'total_page'   => $data->lastPage(),
         'users'       =>  UserResource::collection($data),
        ];
        return $this->apiSuccess($response, 'All users List');
    }

    public function create(CreateRequest $request)
    {
        try {

            $this->userService->create($request->validated());
            return response()->json(['status' => true, 'message' => 'New admin created successfully.']);
        } catch(Exception $ex) {
            return response()->json(['success' => false, 'message' => $ex]);
        }
    }

    public function updatePassword(PasswordUpdateRequest $request, $id)
    {
        try {
            $user = auth()->user()->id;
            if($user == $id) {
                return response(['status' => true, 'message' => 'You are not authorized to update your own password.'], 403);
            }
            $this->userService->updatePassword($id, $request->validated());
            return response()->json(['status' => true, 'message' => 'Password updated successfully.']);
        } catch(Exception $ex) {
            return response()->json(['success' => false, 'message' => $ex]);
        }
    }

    public function updateInfo(InformationUpdateRequest $request, $id)
    {
        try {
            $user = auth()->user()->id;
            if($user == $id) {
                return response(['status' => true, 'message' => 'You are not authorized to update your own information.'], 403);
            }
            $this->userService->updateInformation($id, $request->validated());
            return response()->json(['status' => true, 'message' => 'Information updated successfully.']);
        } catch(Exception $ex) {
            return response()->json(['success' => false, 'message' => $ex]);
        }
    }

    /**
     * Remove the user.
     */
    public function destroy($id)
    {
        try {
            $user = auth()->user()->id;
            if($user == $id) {
                return response(['status' => true, 'message' => 'You are not authorized to delete yourself.'], 403);
            }
            $this->userService->delete($id);
            return response()->json(['status' => true, 'message' => 'User deleted successfully.']);
        } catch(Exception $ex) {
            return response()->json(['success' => false, 'message' => $ex]);
        }

    }
}
