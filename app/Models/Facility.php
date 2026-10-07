<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = ['province_id', 'district_id', 'facility_type_id', 'name'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function facilityType()
    {
        return $this->belongsTo(FacilityType::class, 'facility_type_id');
    }
}
