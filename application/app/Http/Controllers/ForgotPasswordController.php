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
    public function forgot(Request $request) {
        
        $details = [
            'token' => '1234'
        ];

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