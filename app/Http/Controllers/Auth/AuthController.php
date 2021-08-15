<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models de publicacao utilizadas
use App\Models\AppSession;
use App\Http\Controllers\Auth\Models\AppLoginModel;
use App\Http\Controllers\Auth\Models\AppSummarySession;
use App\Models\Estudante;
use Illuminate\Support\Facades\Hash;

class AuthController extends UniversityMarketController {

  private $authTokenKey = "um-auth-token";

  public function loginEstudante(Request $request) {

    $model = $this->cast($request, AppLoginModel::class);

    $model->validar();

    $estudante = Estudante::where('email', $model->email)->first();

    if (is_null($estudante) || !Hash::check($model->senha, $estudante->senha))
      throw new \Exception("E-mail ou senha incorretos");

    $activatedSession = AppSession::where('usuarioId', $estudante->estudanteId)->first();

    // Retornar token da sessao ativa
    if (!is_null($activatedSession))
      return response()->json(new AppSummarySession($activatedSession->sessionToken));

    $session = new AppSession();

    $session->usuarioId = $estudante->estudanteId;
    $session->sessionToken = $this->generateSessionToken($estudante->email);
    $session->dataHoraExpiracao = $this->generateExpirationDate(15);

    $session->save();

    return response()->json(new AppSummarySession($session->sessionToken));
  }

  private function generateSessionToken($email) {

    $mail = explode('@', $email)[0];
    $base = time().'_'.$mail;

    return Hash::make($base);
  }

  private function generateExpirationDate($minutes = 60) {

    $timestamp = time() + $minutes * 60; // now + ($minutes * 60 seconds)

    return date($this->dateTimeFormat, $timestamp);
  }
}