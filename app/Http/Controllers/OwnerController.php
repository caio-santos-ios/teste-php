<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Owner;

class OwnerController extends Controller
{
    public function index()
    {
        $owners = Owner::all();

        // $token = csrf_token();

        return response()->json($owners);
    }

    public function store(Request $request)
    {
        
        $data = $request->validate([
            'name' => '',
            'cpf' => '',
            'age' => '',
            'gender' => ''
        ]);

        $findOwner = Owner::where('cpf', $data['cpf'])->first();

        if($findOwner){
            return response()->json($findOwner, 200);
        };
        
        $owner = Owner::create($data);

        return response()->json($owner, 201);
    }

    public function show(string $id)
    {
        $findOwner = Owner::find($id);

        if(!$findOwner){
            return response()->json(["message" => "Usuário não encontrado"], 404);
        };

        return response()->json($findOwner);
    }

    public function update(Request $request, string $id)
    {
        $findOwner = Owner::find($id);

        if(!$findOwner){
            return response()->json(["message" => "Usuário não encontrado"], 404);
        };

        $data = $request->validate([
            'name' => '',
            'cpf' => '',
            'age' => '',
            'gender' => ''
        ]);

        return response()->json($data);
    }

    public function destroy(string $id)
    {
        $findOwner = Owner::find($id);

        if(!$findOwner){
            return response()->json(["message" => "Usuário não encontrado"], 404);
        };

        $findOwner->delete();

        return response()->json([], 204);
    }
}
