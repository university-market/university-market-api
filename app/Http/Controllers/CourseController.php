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

    
    public function obterCursos() {

        $results = null;

        $results = DB::select('select * from courses');

        return $results;
    }

    public function obterCursoById($id = null) {

        $results = null;

        $results = DB::select('select course_name from courses where id = :id',['id'=> $id]);

        return $results;
    }

}
