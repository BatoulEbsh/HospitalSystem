<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use App\Traits\ReturnResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    use ReturnResponse;

    public function index()
    {
        $doctors = Doctor::query()->select(['*'])->get();
        return $this->returnData('doctors', $doctors);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctorName' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string',
            'department_id' => 'required|exists:departments,id'
        ]);
        if ($validator->fails()) {
            return $this->returnError(422, $validator->errors());
        }
        $user = new User();
        $user->fill([
            'name' => $request['doctorName'],
            'email' => $request['email'],
            'password' => $request['password'],
        ]);
        $user->save();
        $user->roles()->attach([3]);
        $doctor = new Doctor();
        $doctor->fill([
            'doctorName' => $request['doctorName'],
            'department_id' => $request['department_id']
        ]);
        $doctor->save();

        return $this->returnSuccessMessage('doctor added successfully');
    }
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctorName' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->returnError(422, $validator->errors());
        }

        $doctors = Doctor::query();

        if ($request->filled('doctorName')) {
            $doctors->where('doctorName', $request['doctorName']);
        }

        $result = $doctors->get();

        return $this->returnData('doctors', $result);
    }
    public function destroy($id)
    {
        $doctor = Doctor::find($id);
        $doctor->delete();
        return $this->returnSuccessMessage('doctor deleted successfully');
    }

    public function departmentDoctors($id)
    {
        $doctors = Doctor::query()->where('department_id', $id)->get();
        return $this->returnData('doctors', $doctors);
    }
}
