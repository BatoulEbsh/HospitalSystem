<?php
namespace App\Traits;


use Illuminate\Support\Facades\Broadcast;
trait Helper
{
    public function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }


}
