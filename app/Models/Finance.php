<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice',
        'form_id'

    ];

    public function patientForms()
    {
        return $this->belongsTo(PatientForm::class, 'form_id');
    }
}
