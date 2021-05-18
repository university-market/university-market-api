<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\hash;
use Illuminate\Http\Request;


// Models utilizadas
use App\Models\User;

class UserController extends BaseController
{
    private $contentType = ['Content-type' => 'application/json'];

    //Criar Usuario
    public function register(Request $request) {

        $results = DB::select('select * from Users where email = :email', ['email' => $request->email]);

        if (count($results) > 0)
            throw new \Exception('Este Email já existe!');

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->date = $request->date;
        $user->senha = Hash::make($request->password);
        
        $user->save();

        return response()->json($user);
    }

    public function auth(Request $request) {

        $results;

        if ($request->email) {

            //Busca os usuarios de acordo com o id informado
            $results = User::where('email',$request->email)->first();

            //Verifica se a senha é a mesma cadastrada no banco
            if(Hash::check($request->password, $results->senha)){
                //return response()->json($results);
                return 'validado';
                throw new \Exception('Senha Validada');

            }else{
                throw new \Exception('Senha Incorreta');
            } 
            //return response()->json($results);
        } else {
            throw new \Exception('email Não informado!');
        }
        

        //return response()->json($results);
    }

}
