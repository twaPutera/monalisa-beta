<?php

namespace App\Http\Controllers\Admin\UserManagement;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\User\UserQueryServices;
use App\Services\User\UserCommandServices;
use App\Http\Requests\User\UserStoreRequest;
use App\Services\User\UserDatatableServices;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Requests\UserChangePasswordRequest;

class UserController extends Controller
{
    protected $userCommandServices;
    protected $userQueryServices;
    protected $userDatatableServices;

    public function __construct(
        UserCommandServices $userCommandServices,
        UserQueryServices $userQueryServices,
        UserDatatableServices $userDatatableServices
    ) {
        $this->userCommandServices = $userCommandServices;
        $this->userQueryServices = $userQueryServices;
        $this->userDatatableServices = $userDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.user-management.user.index');
    }

    public function store(UserStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->userCommandServices->store($request);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User has been created successfully',
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function show($id)
    {
        try {
            $data = $this->userQueryServices->findById($id);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $request->id = $id;
            $data = $this->userCommandServices->update($request, $id);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User has been updated successfully',
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $data = $this->userCommandServices->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'User has been deleted successfully',
            ]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function changePassword(UserChangePasswordRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = $this->userCommandServices->changePassword($request, $id);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Password has been changed successfully',
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function datatable(Request $request)
    {
        $datatable = $this->userDatatableServices->datatable($request);

        return $datatable;
    }
}
