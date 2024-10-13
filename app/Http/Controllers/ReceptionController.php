<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Traits\ReturnResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReceptionController extends Controller
{
    use ReturnResponse;
    public function index()
    {
        $reception = User::query()->select(['*'])
            ->join('role_user as u','users.id','=','u.user_id')
            ->where('role_id',2)->get();
        return $this->returnData('receptions', $reception);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->returnError(422, $validator->errors());
        }
        $user = new User();
        $user->fill([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password'],
        ]);
        $user->save();
        $user->roles()->attach([2]);
        return $this->returnSuccessMessage('reception added successfully');
    }

}
