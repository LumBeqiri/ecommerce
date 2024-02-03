<?php

namespace App\Http\Controllers\Auth\Staff;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\RegisterStaffRequest;
use App\Http\Resources\StaffResource;
use App\Http\Resources\UserResource;
use App\Models\Staff;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RegisterStaffController extends ApiController
{
    public function __invoke(RegisterStaffRequest $request): JsonResponse
    {
        $response = null;
        DB::beginTransaction();

        try {

            $data = $request->validated();
            $data['password'] = bcrypt($data['password']);
            $data['verification_token'] = User::generateVerificationCode();

            $user = User::create([
                'email' => $request->input('email'),
                'password' => $data['password'],
            ]);

            $user->assignRole($request->input('role'));

            $staff = Staff::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'position' => $request->input('role'),
                'phone' => $request->input('phone'),
                'city' => $request->input('city'),
                'status' => $request->input('status'),
                'notes' => $request->input('notes'),
                'address' => $request->input('address'),
                'vendor_id' => $request->input('vendor_id'),
                'country_id' => $request->input('country_id'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'user_id' => $user->id,
            ]);

            $token = $user->createToken('ecommerceToken')->plainTextToken;

            $response = [
                'user' => new UserResource($user),
                'buyer' => new StaffResource($staff),
                'token' => $token,
            ];

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return $this->showError(message: $e->getMessage());
        }

        return $this->showMessage($response, 201);
    }
}
