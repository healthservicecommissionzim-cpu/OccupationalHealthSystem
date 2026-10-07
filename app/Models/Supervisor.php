<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    protected $fillable = ['department_id', 'designation_title', 'role_type', 'hierarchy_level', 'is_hod', 'description'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
