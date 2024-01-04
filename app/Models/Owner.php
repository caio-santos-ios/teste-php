<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vehicle;
use App\Models\RevisionVehicle;

class Owner extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'cpf', 'age', 'gender'];

    public function vehicles()  
    {
        return $this->hasMany(Vehicle::class);
    }

    public function revisionVehicles()
    {
        return $this->hasMany(RevisionVehicle::class);
    }
}
