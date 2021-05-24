<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\hash;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;


// Models utilizadas
use App\Models\User;

class UserController extends BaseController
{
    private $contentType = ['Content-type' => 'application/json'];

    //Criar Usuario
    public function register(Request $request) {

        if (!$request->email && $request->password && $request->name){
            throw new \Exception('Dados Incompletos!');
        }

            $results = DB::select('select * from Users where email = :email', ['email' => $request->email]);

            if (count($results) > 0){
                throw new \Exception('E-mail já cadastrado!');
            }

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->date = $request->date; 
            $user->curso = $request->curso;
            $user->bloqued = 0;
            $user->senha = Hash::make($request->password);

            $user->save();


        // Validaçãoreturn response()->json($user);
    }

    public function auth(Request $request)
    {

        $results = null;

        if ($request->email && $request->password) {

            //Busca os usuarios de acordo com o id informado
            $results = User::where('email', $request->email)->firstOrFail();

            //Verifica se a senha é a mesma cadastrada no banco
            if (!Hash::check($request->password, $results->senha)) {
                throw new \Exception('Senha Incorreta!');
            }
            //Validação de usuário bloqueado
            if ($results->bloqued) {
                throw new \Exception('Usuário não bloqueado!');
            }

            return $results;
        } else {
            throw new \Exception('Informe todos os campos de login!');
        }
    }

    public function list(Request $request)
    {
        $results = null;

        if (!$request)
        {
            throw new \Exception('Dados Incompletos!');
        }

        if($request->id)
        {
            $results = User::where('id', $request->id)->first();
            return $results;
        }

        $results = DB::select('select * from Users');
        return $results;
    }

    public function bloqued(Request $request)
    {
        $results = null;

        if (!$request)
        {
            throw new \Exception('Dados Incompletos!');
        }

        if(!$request->id)
        {
            throw new \Exception('ID não informado');
        }
        
        $results = User::where('id', $request->id)->first();
        return $results->bloqued;

    }

    public function blockade(Request $request)
    {
        $results = null;

        if (!$request)
        {
            throw new \Exception('Dados Incompletos para bloqueio');
        }

        if(!$request->id)
        {
            throw new \Exception('ID não informado');
        }
        
        DB::table('users')->where('id', $request->id)->update(['bloqued' => 1]);
    
    }

    public function unlock(Request $request)
    {
        $results = null;

        if (!$request)
        {
            throw new \Exception('Dados Incompletos para desbloqueio');
        }

        if(!$request->id)
        {
            throw new \Exception('ID não informado');
        }
        
        DB::table('users')->where('id', $request->id)->update(['bloqued' => 0]);
    
    }

}
