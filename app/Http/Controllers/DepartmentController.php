<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Traits\ReturnResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    use ReturnResponse;

    public function index()
    {
        $departments = Department::query()
            ->select(['*'])
            ->get();
        return $this->returnData('departments', $departments);
    }

    public function departmentsWithDoctors()
    {
        $departments = Department::query()
            ->join('doctors as doc', 'departments.id', '=', 'doc.department_id')
            ->select(['departments.id', 'departments.departmentName'])->with('doctors')
            ->get();
        return $this->returnData('departments', $departments);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'departmentName' => 'required|string'
        ]);
        if ($validator->fails()) {
            return $this->returnError(422, $validator->errors());
        }
        $department = new Department();
        $department->fill([
            'departmentName' => $request['departmentName']
        ]);
        $department->save();
        return $this->returnSuccessMessage('department added successfully');
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'departmentName' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->returnError(422, $validator->errors());
        }

        $department = Department::query();

        if ($request->filled('departmentName')) {
            $department->where('departmentName', $request->input('departmentName'))
                ->withCount('doctors');
        }

        $result = $department->get();

        return $this->returnData('departments', $result);
    }


    public function destroy($id)
    {
        $doctor = Department::find($id);
        $doctor->delete();
        return $this->returnSuccessMessage('department deleted successfully');
    }
}
