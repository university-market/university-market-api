<?php

namespace App\Http\Controllers\ForgotSenha;

use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;

// Models utilizadas
use App\Models\User;
use App\Http\Controllers\Forgotsenha\Models\ForgotModel;

class ForgotPasswordController extends UniversityMarketController{   

    public $token;

    public function forgot(Request $request) {

        if (!$request)
        {
            throw new \Exception('Dados Incompletos para Recuperação de senha');
        }
        
        $results = DB::select('select * from Users where email = :email', ['email' => $request->email]);

        if (count($results) == 0){
            throw new \Exception('E-mail não cadastrado!');
        }

        $this->token = rand(100000, 999999);

        DB::table('users')->where('email', $request->email)->update(['token' => $this->token]);
        $resultnome = User::where('email', $request->email)->first();

        /*
        $details = [
            'token' => '1234'
        ];*/

        $details = new \stdClass();
        $details->token = $this->token;
        $details->name = $resultnome->name;

        Mail::to($request->email)->send(new \App\Mail\ForgotSenhaMail($details));
        
        /*Mail::to([
        'leonardopimentellopes@gmail.com',
        'machado.matheus9265@gmail.com',
        'jvkm2001@gmail.com',
        'fe.wesleybasso@gmail.com'
        ])->send(new \App\Mail\SendMail($details));
        */
    }
    
    public function checksenha(Request $request){

        $model = $this->cast($request, ForgotModel::class);

        $model->validar();

        $resultstoken = User::where('email', $model->email)->first();

        if($model->token != $resultstoken->token ){
            throw new \Exception('token informado não confere');
        }

        if($model->senha != $model->confirmasenha){
            throw new \Exception('senhas não conferem');
        }

        $hashsenha = Hash::make($model->senha);

        DB::table('users')->where('email', $model->email)->update(['senha' => $hashsenha]);

        DB::table('users')->where('email', $model->email)->update(['token' => 0]);

    }
}


?>