<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Owner;
use Illuminate\Http\Exceptions\HttpResponseException;

class OwnerExisty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $data = $request->input();

        $findOwner = Owner::where('cpf', $data['cpf'])->first();

        if($findOwner){
            throw new HttpResponseException(response()->json(["message" => "Usuário já cadastrado"], 409));    
        }

        return $next($request);
    }
}
