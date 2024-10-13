<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'doctorName',
        'department_id'
    ];

    public function patientForms()
    {
        return $this->hasMany(PatientForm::class, 'doctor_id');
    }
    public function department(){
        return $this->belongsTo(Department::class,'department_id');
    }
}
