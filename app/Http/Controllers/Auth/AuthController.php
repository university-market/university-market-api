<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models de publicacao utilizadas
// use App\Models\Session;
use App\Http\Controllers\Auth\Models\LoginModel;

class AuthController extends UniversityMarketController {

  private $authTokenKey = "um-auth-token";

  public function login(Request $request) {

    $headers = $request->headers->all();

    $token = $headers[$this->authTokenKey];

    $model = $this->cast($request, LoginModel::class);

    // return;
  }
}