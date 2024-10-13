<?php

namespace App\Http\Controllers;

use App\Models\PatientForm;
use App\Traits\Helper;
use App\Traits\ReturnResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    use ReturnResponse;

    public function index()
    {
        $patients = PatientForm::query()
            ->join('departments as dp', 'patient_forms.department_id', '=', 'dp.id')
            ->join('doctors as doc', 'patient_forms.doctor_id', '=', 'doc.id')
            ->select([
                'patient_forms.patientName',
                'patient_forms.age',
                'patient_forms.address',
                'patient_forms.phoneNumber',
                'patient_forms.chronicDiseases',
                'patient_forms.bloodType',
                'patient_forms.isSmoking',
                'patient_forms.invoiceId',
                'patient_forms.diagnosis',
                'patient_forms.invoice',
                'patient_forms.state',
                'patient_forms.rejectReason',
                'patient_forms.prescription',
                'patient_forms.user_id',
                'patient_forms.department_id',
                'patient_forms.doctor_id',
                'dp.departmentName',
                'doc.doctorName'
            ])
            ->get();

        return $this->returnData('patients', $patients);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patientName' => 'required|string',
            'age' => 'required|numeric',
            'address' => 'required|string',
            'phoneNumber' => 'required|string',
            'chronicDiseases' => 'required|string',
            'bloodType' => 'required|string',
            'isSmoking' => 'required|boolean',
            'invoice' => 'required|numeric',
            'invoiceId' => 'required|numeric',
            'doctor_id' => 'required|exists:doctors,id',
            'department_id' => 'required|exists:departments,id'
        ]);
        if ($validator->fails()) {
            return $this->returnError(422, $validator->errors());
        }
        $patient = new PatientForm();
        $user = Auth::id();
        $patient->fill([
            'patientName' => $request['patientName'],
            'age' => $request['age'],
            'address' => $request['address'],
            'phoneNumber' => $request['phoneNumber'],
            'chronicDiseases' => $request['chronicDiseases'],
            'bloodType' => $request['bloodType'],
            'isSmoking' => $request['isSmoking'],
            'invoice' => $request['invoice'],
            'invoiceId' => $request['invoiceId'],
            'user_id' => $user,
            'doctor_id' => $request['doctor_id'],
            'department_id' => $request['department_id']
        ]);
        $patient->save();
        return $this->returnSuccessMessage('patient added successfully');
    }

    public function accept($id)
    {
        $patient = PatientForm::query()->find($id);
        if($patient->state != 'waiting'){
            return $this->returnError(201,'patientForm not waiting');
        }
        else
        $patient->update(['state' => 'accepted']);
        return $this->returnSuccessMessage('patientForm accepted successfully');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'invoice' => 'required|numeric',
            'diagnosis' => 'required|string',
            'prescription' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->returnError(422, $validator->errors());
        }
        $patient = PatientForm::query()->find($id);
        $patientInvoice = $patient->invoice;
        if($patient->state != 'accepted'){
            return $this->returnError(201,'patientForm not able to updating');
        }
        else
        $patient->fill([
            'invoice' => $patientInvoice + $request['invoice'],
            'diagnosis' => $request['diagnosis'],
            'prescription' => $request['prescription'],
        ]);
        $patient->save();
        return $this->returnSuccessMessage('patientForm updated successfully');

    }

    public function reject(Request $request, $id)
    {
        $patient = PatientForm::query()->find($id);
        $validator = Validator::make($request->all(), ['rejectReason' => 'required|string',]);
        if ($validator->fails()) {
            return $this->returnError(422, $validator->errors());
        }
        if($patient->state != 'waiting'){
            return $this->returnError(201,'patientForm not waiting');
        }
        else
        $patient->fill([
            'rejectReason' => $request['rejectReason'],
        ]);
        $patient->update(['state' => 'reject']);
        $patient->save();
        return $this->returnSuccessMessage('patient is reject');

    }

    public
    function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patientName' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->returnError(422, $validator->errors());
        }

        $patient = PatientForm::query();

        if ($request->filled('patientName')) {
            $patient->where('patientName', $request['patientName']);
        }

        $result = $patient->get();

        return $this->returnData('patients', $result);
    }

    public
    function show($id)
    {
        $patient = PatientForm::query()
            ->join('departments as dp', 'patient_forms.department_id', '=', 'dp.id')
            ->join('doctors as doc', 'patient_forms.doctor_id', '=', 'doc.id')
            ->where('patient_forms.id', '=', $id)
            ->select([
                'patient_forms.patientName',
                'patient_forms.age',
                'patient_forms.address',
                'patient_forms.phoneNumber',
                'patient_forms.chronicDiseases',
                'patient_forms.bloodType',
                'patient_forms.isSmoking',
                'patient_forms.invoiceId',
                'patient_forms.diagnosis',
                'patient_forms.invoice',
                'patient_forms.state',
                'patient_forms.rejectReason',
                'patient_forms.prescription',
                'patient_forms.user_id',
                'patient_forms.department_id',
                'patient_forms.doctor_id',
                'dp.departmentName',
                'doc.doctorName'
            ])
            ->get();

        return $this->returnData('patient', $patient);
    }

    public
    function doctorPatient($id)
    {
        $patients = PatientForm::query()
            ->join('departments as dp', 'patient_forms.department_id', '=', 'dp.id')
            ->join('doctors as doc', 'patient_forms.doctor_id', '=', 'doc.id')
            ->where('patient_forms.doctor_id', '=', $id)
            ->select([
                'patient_forms.patientName',
                'patient_forms.age',
                'patient_forms.address',
                'patient_forms.phoneNumber',
                'patient_forms.chronicDiseases',
                'patient_forms.bloodType',
                'patient_forms.isSmoking',
                'patient_forms.invoiceId',
                'patient_forms.diagnosis',
                'patient_forms.invoice',
                'patient_forms.state',
                'patient_forms.rejectReason',
                'patient_forms.prescription',
                'patient_forms.user_id',
                'patient_forms.department_id',
                'patient_forms.doctor_id',
                'dp.departmentName',
                'doc.doctorName'
            ])
            ->get();
        return $this->returnData('patients', $patients);
    }

    public function okPatient()
    {
        $patients = PatientForm::query()->where('state', 'accepted')->get();
        return $this->returnData('patients', $patients);
    }

    public
    function destroy($id)
    {
        $doctor = PatientForm::find($id);
        $doctor->delete();
        return $this->returnSuccessMessage('patient deleted successfully');
    }
}
