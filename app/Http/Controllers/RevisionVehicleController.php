<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Owner;
use App\Models\RevisionVehicle;

class RevisionVehicleController extends Controller
{
    public function index()
    {
        $findRevisionVehicle = RevisionVehicle::all();
        
        return response()->json($findRevisionVehicle);    
    }

    public function store(Request $request, string $idVehicle)
    {
        $findVehicle = Vehicle::where('id', $idVehicle)->first();
        
        if(!$findVehicle){
            return response()->json(["message" => "Veiculo não encontrado"], 404);
        };

        $data = $request->validate([
            'type_revision' => '',
            'description' => '',
            'value' => '',
            'owner_id' => ''
        ]);

        $findOwner = Owner::where('id', $data['owner_id'])->first();
        
        if(!$findOwner){
            return response()->json(["message" => "Usuário não encontrado"], 404);
        };

        $revision = $findVehicle->revisionVehicles()->create($data);

        return response()->json([$revision]);
    }

    public function show(string $id)
    {
        $findRevision = RevisionVehicle::find($id);

        if(!$findRevision){
            return response()->json(["message" => "Revisão não encontrado"], 404);
        };

        return response()->json($findRevision);
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        $findRevision = RevisionVehicle::find($id);

        if(!$findRevision){
            return response()->json(["message" => "Revisão não encontrado"], 404);
        };

        $findRevision->delete();

        return response()->json([], 204);
    }
}
