<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = ['name', 'category', 'salary_grade', 'is_clinical', 'description'];
}
