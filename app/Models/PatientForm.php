<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'patientName',
        'age',
        'address',
        'phoneNumber',
        'chronicDiseases',
        'bloodType',
        'isSmoking',
        'invoiceId',
        'diagnosis',
        'invoice',
        'state',
        'rejectReason',
        'prescription',
        'user_id',
        'department_id',
        'doctor_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function Finance()
    {
        return $this->hasMany(Finance::class, 'form_id');
    }
}
