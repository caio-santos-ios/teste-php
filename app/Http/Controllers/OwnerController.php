<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Owner;


class OwnerController extends Controller
{
    public function index(Request $request)
    {
        $owners = Owner::with(['vehicles', 'revisionVehicles' => function($query) {
            $query->where('is_done', false);
        }])->get();
        
        return response()->json($owners);
    }

    public function store(Request $request)
    {
        
        $data = $request->all();

        $findOwner = Owner::where('cpf', $data['cpf'])->first();

        if($findOwner){
            return response()->json($findOwner, 200);
        };
        
        $owner = Owner::create($data);

        return response()->json($owner, 201);
    }

    public function show(string $id)
    {
        $findOwner = Owner::with(['revisionVehicles', 'vehicles'])->first();

        if(!$findOwner){
            throw new HttpResponseException(response()->json(["message" => "Usuário não encontrado"], 404));
        };

        return response()->json($findOwner);
    }

    public function update(Request $request, string $id)
    {
        $findOwner = Owner::find($id);

        if(!$findOwner){
            throw new HttpResponseException(response()->json(["message" => "Usuário não encontrado"], 404));
        };

        $data = $request->all();

        $ownerUpdate = Owner::find($id);

        $ownerUpdate->update($data);

        return response()->json($ownerUpdate);
    }

    public function destroy(string $id)
    {
        $findOwner = Owner::find($id);

        if(!$findOwner){
            throw new HttpResponseException(response()->json(["message" => "Usuário não encontrado"], 404));
        };

        $findOwner->vehicles()->delete();

        $findOwner->delete();

        return response()->json([], 204);
    }
}
