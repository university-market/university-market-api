<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

// Models utilizadas
use App\Models\Usuario;

class Controller extends BaseController
{
    private $contentType = ['Content-type' => 'application/json'];

    public function criarUsuario($name) {

        $results = DB::select('select * from Users where name = :name', ['name' => $name]);

        if (count($results) > 0)
            throw new \Exception('Este usuario já existe!');

        $user = new Usuario();
        $user->name = $name;

        $user->save();

        return response()->json($user);
    }

    public function obterUsuario($id = null) {

        $results = [];

        if ($id) {
            
            $results = DB::select('select * from Users where id = :id', ['id' => $id]);

        } else {

            $results = DB::select('select * from Users');

        }

        return response()->json($results);
    }

    public function notObterUsuario($name) {

        $results = Usuario::where('name', '!=', $name)->orderBy('name', 'ASC')->get();

        return response()->json($results);
    }
}
