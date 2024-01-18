<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Owner;
use Illuminate\Http\Exceptions\HttpResponseException;


class VehicleController extends Controller
{
   
    public function index()
    {
        
        $vehicles = Vehicle::with(['owner', 'revisionVehicles'])->get();
        
        return response()->json($vehicles);    
    }


    public function store(Request $request)
    {
        $data = $request->input();

        $owner = Owner::find($data['owner_id']);

        if(!$owner){
            throw new HttpResponseException(response()->json(["message" => "Proprietario não encontrado"], 404));
        };

        
        $findVehicle = Vehicle::where('plate', $data['plate'])->first();
        
        
        if($findVehicle){
            throw new HttpResponseException(response()->json(["message" => "Placa inválida"], 400));
        }
        
        $vehicle = $owner->vehicles()->create($data);

        return response()->json($vehicle, 201);    
    }

    public function show(string $id)
    {
        $findVehicle = Vehicle::find($id);

        if(!$findVehicle){
            throw new HttpResponseException(response()->json(["message" => "Veiculo não encontrado"], 404));
        };

        $vehiclesOwner = $findVehicle->with('revisionVehicles')->get();

        return response()->json($vehiclesOwner);
    }

    public function update(Request $request, string $id)
    {
        $findVehicle = Vehicle::find($id);

        if(!$findVehicle){
            throw new HttpResponseException(response()->json(["message" => "Veiculo não encontrado"], 404));
        };

        $data = $request->input();

        $vehicleUpdate = Vehicle::find($id);

        $vehicleUpdate->update($data);

        return response()->json($vehicleUpdate);
    }

    public function destroy(string $id)
    {
        $findVehicle = Vehicle::find($id);

        if(!$findVehicle){
            throw new HttpResponseException(response()->json(["message" => "Veiculo não encontrado"], 404));
        };

        $findVehicle->delete();

        return response()->json([], 204);
    }
}
