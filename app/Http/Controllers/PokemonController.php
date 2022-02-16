<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;

class PokemonController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $pokemon = Pokemon::orderBy('id','asc')->paginate(10);
        return $pokemon;
    }

    public function putPokemon(Request $req,$id){
        $pokemon = Pokemon::find($id);
        $pokemon->identifier = $req->identifier;
        $pokemon->species_id = $req->species_id;
        $pokemon->height = $req->height;
        $pokemon->weight = $req->weight;
        $pokemon->base_experience = $req->base_experience;
        $pokemon->save();

        return response()->json(['message' =>'updated'],200);
    }

    public function postPokemonData(){
        $file = fopen(storage_path()."/pokemon/pokemon.csv","r");
        while(! feof($file)){
            $line = fgetcsv($file);
            
            if(gettype($line) != 'array'){
                continue;
            }

            if($line[0] == 'id'){
                continue;
            }
            $pokemon = new Pokemon();
            $pokemon->identifier = $line[1];
            $pokemon->species_id = $line[2];
            $pokemon->height = $line[3];
            $pokemon->weight = $line[4];
            $pokemon->base_experience = $line[5];
            $pokemon->order = $line[6];
            $pokemon->is_default = $line[7];
            $pokemon->save();
        }
        fclose($file);

        return "Done";
    }
}
