<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'state',
        'description',
        'number',
        'department_id'
    ];
    public function department(){
        return $this->belongsTo(Department::class,'department_id');
    }
}
