<?php

namespace App\Http\Controllers\Auth\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\RegisterBuyerRequest;
use App\Http\Resources\BuyerResource;
use App\Http\Resources\UserResource;
use App\Models\Buyer;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RegisterBuyerController extends ApiController
{
    public function __invoke(RegisterBuyerRequest $request): JsonResponse
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

            $buyer = Buyer::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'city' => $request->input('city'),
                'country_id' => $request->input('country_id'),
                'zip' => $request->input('zip'),
                'shipping_address' => $request->input('shipping_address'),
                'phone' => $request->input('phone'),
                'user_id' => $user->id,
            ]);

            $token = $user->createToken('ecommerceToken')->plainTextToken;

            $response = [
                'user' => new UserResource($user),
                'buyer' => new BuyerResource($buyer),
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
