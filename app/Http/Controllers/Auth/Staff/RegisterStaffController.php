<?php

namespace App\Http\Controllers\Auth\Staff;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\RegisterStaffRequest;
use App\Http\Resources\UserResource;
use App\Models\Staff;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterStaffController extends ApiController
{
    public function __invoke(RegisterStaffRequest $request): JsonResponse
    {
        $response = null;

        try {

            $data = $request->validated();
            $data['password'] = bcrypt($data['password']);
            $data['verification_token'] = User::generateVerificationCode();

            /**
             * @var User $user
             */
            $user = User::create([
                'email' => $request->input('email'),
                'password' => $data['password'],
            ]);

            $user->syncRoles($request->input('role'));

            Staff::create([
                'position' => $request->input('role'),
                'status' => $request->input('status'),
                'notes' => $request->input('notes'),
                'address' => $request->input('address'),
                'vendor_id' => $request->input('vendor_id'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'user_id' => $user->id,
            ]);

            $user->user_settings()->create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'phone' => $request->input('phone'),
                'city' => $request->input('city'),
                'country_id' => $request->input('country_id'),
                'zip' => $request->input('zip'),
            ]);
            $token = $user->createToken('ecommerceToken')->plainTextToken;

            $response = [
                'user' => new UserResource($user),
                'token' => $token,
            ];

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return $this->showError(message : $e->getMessage(), code: $e->getCode());
        }

        return $this->showMessage($response, 201);
    }
}
