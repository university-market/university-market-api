<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\Data\AuthCommonData;
use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models de publicacao utilizadas
use App\Models\Session\AppSession;
use App\Http\Controllers\Auth\Models\AppLoginModel;
use App\Http\Controllers\Auth\Models\AppSummarySession;
use App\Models\Estudante\Estudante;
use Illuminate\Support\Facades\Hash;

class AuthController extends UniversityMarketController {

  public function loginEstudante(Request $request) {

    $model = $this->cast($request, AppLoginModel::class);

    $model->validar();

    $estudante = Estudante::where('email', $model->email)->first();

    if (is_null($estudante) || !Hash::check($model->senha, $estudante->hashSenha))
      throw new \Exception("E-mail ou senha incorretos");

    $activatedSession = AppSession::where('estudanteId', $estudante->estudanteId)->first();

    // Retornar token da sessao ativa
    if (!is_null($activatedSession))
      return response()->json(new AppSummarySession($activatedSession->sessionToken));

    // Gerar tempo de expiracao da sessao em minutos
    $expiration = $this->generateExpirationDate();

    $session = new AppSession();

    $session->estudanteId = $estudante->estudanteId;
    $session->sessionToken = $this->generateSessionToken($estudante->email);
    $session->expirationTime = $expiration;

    $session->save();

    return response()->json(new AppSummarySession($session->sessionToken));
  }

  private function generateSessionToken($email) {

    $mail = explode('@', $email)[0];
    $base = time().'_'.$mail;

    return Hash::make($base);
  }

  private function generateExpirationDate() {

    $minutes = AuthCommonData::getSessionDefaultExpirationTime();
    
    $timestamp = time() + $minutes * 60; // now + ($minutes * 60 seconds)

    return $timestamp;
  }
}