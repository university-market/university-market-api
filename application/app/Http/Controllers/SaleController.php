<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\UniversityMarketController;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;


// Models utilizadas
use App\Models\Sale;

class SaleController extends BaseController
{
    private $contentType = ['Content-type' => 'application/json'];

    //Criar Usuario
    public function listByCourseId($id = null) {

        $results = null;

        if (!$id) {
            $results = DB::select('select * from publicacao');
            return $results;
        } else {
            $results = DB::select('select publicacaoId,
                                          titulo,
                                          descricao,
                                          valor,
                                          pathimagem,
                                          name
                                    from  publicacao 
                                     join users 
                                       on userId = id 
                                    where id = 4 
                                      and cursoId = :id',['id'=> $id]);
            return $results;
        }
        
    }

}
