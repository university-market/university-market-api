<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;

// Models utilizadas
use App\Models\User;

class ForgotPasswordController extends BaseController
{   
    public $token;

    public function forgot(Request $request) {


        if (!$request)
        {
            throw new \Exception('Dados Incompletos para Recuperação de senha');
        }
        
        $results = DB::select('select * from Users where email = :email', ['email' => $request->email]);

        if (count($results) = 0){
            throw new \Exception('E-mail não cadastrado!');
        }

        $this->token = rand(1000, 9999);

        DB::table('users')->where('email', $request->email)->update(['token' => $this->token]);

        /*
        $details = [
            'token' => '1234'
        ];*/

        $details = new \stdClass();
        $details->token = $this->token;

        Mail::to($request->email)->send(new \App\Mail\ForgotSenhaMail($details));
        
        /*Mail::to([
        'leonardopimentellopes@gmail.com',
        'machado.matheus9265@gmail.com',
        'jvkm2001@gmail.com',
        'fe.wesleybasso@gmail.com'
        ])->send(new \App\Mail\SendMail($details));
        */
    }
}


?>