<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Owner;
use App\Models\Vehicle;

class RevisionVehicle extends Model
{
    use HasFactory;

    protected $fillable = ['type_revision', 'value', 'description', 'is_done', 'owner_id', 'vehicle_id'];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
