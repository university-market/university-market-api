<?php

namespace App\Http\Controllers;

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
            $results = DB::select('select * from sales');
            return $results;
        } else {
            $results = DB::select('select * from sales where course_id = :id',['id'=> $id]);
            return $results;
        }
        
    }

}
