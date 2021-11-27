<?php

namespace App\Http\Controllers\Charts\Denuncia;

use Illuminate\Http\Request;

// Base
use App\Base\Controllers\UniversityMarketController;
use App\Base\Exceptions\UniversityMarketException;
use App\Base\Logs\Logger\UniversityMarketLogger;
use App\Base\Logs\Type\StdLogChange;
use App\Base\Logs\Type\StdLogType;
use App\Base\Resource\UniversityMarketResource;

// Entidades
use App\Models\Denuncia\Denuncia;

class DenunciaChartsController extends UniversityMarketController {

    /**
     * Quantificar denuncias
     * 
     * @method quantificar
     * @param Request $request Instância de requisição
     * 
     * @type Http GET
     * @route `/charts/denuncia/quantificar`
     */
    public function quantificar(Request $request) {

        $denuncias = Denuncia::get();
        
        return $this->response($denuncias);
    }

}