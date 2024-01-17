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
        $findRevisionVehicle = RevisionVehicle::with(['owner', 'vehicle'])->where('is_done', false)->get();
        
        return $findRevisionVehicle;  
    }

    public function store(Request $request, string $idVehicle)
    {
        $findVehicle = Vehicle::where('id', $idVehicle)->first();
        
        if(!$findVehicle){
            throw new HttpResponseException(response()->json(["message" => "Veiculo não encontrado"], 404));
        };
        
        $data = $request->input();

        $findOwner = Owner::where('id', $data['owner_id'])->first();
        
        if(!$findOwner){
            throw new HttpResponseException(response()->json(["message" => "Proprietario não encontrado"], 404));
        };

        $revision = $findVehicle->revisionVehicles()->create($data);

        return response()->json([$revision]);
    }

    public function show(string $id)
    {
        $findRevision = RevisionVehicle::find($id);

        if(!$findRevision){
            throw new HttpResponseException(response()->json(["message" => "Revisão não encontrado"], 404));
        };

        return response()->json($findRevision);
    }

    public function update(Request $request, string $id)
    {
        $findRevision = RevisionVehicle::find($id);

        if(!$findRevision){
            throw new HttpResponseException(response()->json(["message" => "Revisão não encontrado"], 404));
        };

        $data = $request->input();

        $revisionUpdate = RevisionVehicle::find($id);

        $revisionUpdate->update($data);

        return response()->json($revisionUpdate);
    }

    public function destroy(string $id)
    {
        $findRevision = RevisionVehicle::find($id);

        if(!$findRevision){
            throw new HttpResponseException(response()->json(["message" => "Revisão não encontrado"], 404));
        };

        $findRevision->delete();

        return response()->json([], 204);
    }
}
