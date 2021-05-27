<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// Models utilizadas
use App\Models\Usuario;

class UserController extends BaseController
{
    private $contentType = ['Content-type' => 'application/json'];

    //Criar Usuario
    public function register(Request $request) {

        if ($request->email && $request->password && $request->name) {

            $results = DB::select('select * from Users where email = :email', ['email' => $request->email]);

            if (count($results) > 0)
                throw new \Exception('E-mail já cadastrado!');

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->date = $request->date; 
            $user->curso = $request->curso;
            $user->bloqued = 0;
            $user->token = 0;
            $user->senha = Hash::make($request->password);

            $user->save();
        
        }


        // Validaçãoreturn response()->json($user);
    }

    public function auth(Request $request)
    {

        $results = null;

        if ($request->email && $request->password) {

            //Busca os usuarios de acordo com o id informado
            $results = User::where('email', $request->email)->first();

            // Validação de email
            if (!$results) {
                throw new \Exception("Usuário não cadastrado!");
            }

            //Verifica se a senha é a mesma cadastrada no banco
            if (!Hash::check($request->password, $results->senha)) {
                throw new \Exception('Senha Incorreta!');
            }
            //Validação de usuário bloqueado
            if ($results->bloqued) {
                throw new \Exception('Usuário não dadasdsa!');
            }

            return $results;
        } else {
            throw new \Exception('Informe todos os campos de login!');
        }
    }

}
