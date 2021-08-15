<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models de publicacao utilizadas
use App\Models\AppSession;
use App\Http\Controllers\Auth\Models\AppLoginModel;

class AuthController extends UniversityMarketController {

  private $authTokenKey = "um-auth-token";

  public function login(Request $request) {

  }
}