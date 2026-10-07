<?php

namespace App\Models;

use Database\Factories\OrganizationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'requires_location',
    ];

    protected $casts = [
        'requires_location' => 'boolean',
    ];

    protected static function newFactory()
    {
        return OrganizationFactory::new();
    }
}
