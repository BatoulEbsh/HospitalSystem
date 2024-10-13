<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'departmentName',
    ];

    public function patientForms()
    {
        return $this->hasMany(PatientForm::class, 'department_id');
    }

    public function equipments()
    {
        return $this->hasMany(Equipment::class, 'department_id');
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'department_id');
    }
}
