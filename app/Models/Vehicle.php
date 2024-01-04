<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Owner;
use App\Models\RevisionVehicle;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['make', 'plate', 'model', 'year', 'owner_id'];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function revisionVehicles()
    {
        return $this->hasMany(RevisionVehicle::class);
    }
}
