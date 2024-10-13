<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Traits\ReturnResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EquipmentController extends Controller
{
    use ReturnResponse;
    public function index()
    {

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'state' => 'required|string',
            'description' => 'required|string',
            'number'=>'required|numeric',
            'department_id' => 'required|exists:departments,id'
        ]);
        if ($validator->fails()) {
            return $this->returnError(422, $validator->errors());
        }
        $equipment =new Equipment();
        $equipment->fill([
            'name'=>$request['name'],
            'state'=>$request['state'],
            'description'=>$request['description'],
            'number'=>$request['number'],
            'department_id'=>$request['department_id']
        ]);
        $equipment->save();
        return $this->returnSuccessMessage('Equipment added successfully');
    }
}
