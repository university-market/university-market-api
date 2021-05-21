<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\hash;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;


// Models utilizadas
use App\Models\User;

class CourseController extends BaseController
{
    private $contentType = ['Content-type' => 'application/json'];

    //Criar Usuario
    public function obterCourse() {

        $results = null;

        $results = DB::select('select * from courses');

        return $results;
    }

}
