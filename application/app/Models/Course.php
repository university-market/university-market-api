<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Course extends Model{
    
    public $timestamps = false; // Nao registrar data/hora criacao/alteracao
    protected $table = 'Courses';
    protected $primaryKey = 'id';

    // Colunas da tabela
    
    protected $id;
    protected $course_name;
    protected $grid_cols;
    protected $grid_rows;
    protected $img_path;
    
}
