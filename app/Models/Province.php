<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = ['name', 'latitude', 'longitude'];

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }

    public function incidents()
    {
        return $this->hasManyThrough(
            Incident::class,
            Facility::class
        );
    }
}
