<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pokemon;

class ApiController extends Controller
{
    public function all(Request $req){

        $result = $this->logIn($req);

        if(!empty($result)){
            return $result;
        }

        $pokemons = Pokemon::select('identifier','species_id','height','weight','base_experience','order','is_default')->get();
        return response()->json($pokemons);
    }

    public function pokemon(Request $req,$id){
                
        $result = $this->logIn($req);

        if(!empty($result)){
            return $result;
        }

        if($pokemon = pokemon::find($id)){
            unset($pokemon->created_at);
            unset($pokemon->updated_at);
            return response()->json($pokemon);
        }

        return response()->json(['message' => 'pokemon not found'],404);
    }

    public function logIn($req){
        $username = $req->header('username');
        $password = $req->header('password');

        if(empty($username) || empty($password)){
            return response()->json(["message" => "log in credentials required"],422);
        }

        if (!\Auth::attempt(['email' => $username, 'password' => $password])) {
            return response()->json(["message" => "Invalid credentials"],401);
        }
    }
}
