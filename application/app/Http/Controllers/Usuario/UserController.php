<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\hash;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;


// Models utilizadas
use App\Models\User;
use App\Http\Controllers\Usuario\Models\CriacaoUserModel;
use App\Http\Controllers\Usuario\Models\LoginUserModel;
use App\Http\Controllers\Usuario\Models\DetalhesUserModel;

class UserController extends UniversityMarketController{
   
    public function emailValidate(Request $request) {
        
        if (!$request->email) {
            throw new \Exception('Email não informado');
        }

        $results = DB::select('select * from Users where email = :email', ['email' => $request->email]);

        if (count($results) > 0){
            throw new \Exception('E-mail já cadastrado!');
        }
        
        return $results;
    
    }
    //Criar Usuario
    public function register(Request $request) {

        $model = $this->cast($request, CriacaoUserModel::class);

        $model->validar();
        
        $results = DB::select('select * from Users where email = :email', ['email' => $model->email]);

        if (count($results) > 0){
            throw new \Exception('E-mail já cadastrado!');
        }

        $user = new User();
        $user->name = $model->name;
        $user->ra = $model->ra;
        $user->email = $model->email;
        $user->telefone = $model->telefone;
        $user->cpf = $request->cpf;
        $user->curso = $request->curso;
        $user->dataNasc = $request->dataNasc;
        $user->senha = Hash::make($model->senha);

        $user->save();

           /* {
            "name":"Leozaosacana",
            "ra":"19904292",
            "email":"leonardopimentellopes@gmail.com",
            "telefone":"41996014579",
            "cpf":"09745529923",
            "dataNasc":"20011011",
            "curso":"ADM",
            "senha":"1234"
        }*/
        // Validaçãoreturn response()->json($user);
    }

    public function auth(Request $request)
    {

        $model = $this->cast($request, LoginUserModel::class);

        $model->validar();

        //Busca os usuarios de acordo com o id informado
        $results = User::where('email', $model->email)->firstOrFail();

        //Verifica se a senha é a mesma cadastrada no banco
        if (!Hash::check($model->senha, $results->senha)) {
            throw new \Exception('Senha Incorreta!');
        }
        //Validação de usuário bloqueado
        if ($results->bloqued) {
            throw new \Exception('Usuário bloqueado!');
        }

        return $results;
        
    }

    public function list(Request $request) {

        $usuario = User::where('id', $request->id)->first();

        if (\is_null($usuario))
            throw new \Exception("usuario não encontrada");

        $model = $this->cast($usuario, DetalhesUserModel::class);

        return response()->json($model);
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
