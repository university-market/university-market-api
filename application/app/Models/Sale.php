<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Sale extends Model{
    
    public $timestamps = false; // Nao registrar data/hora criacao/alteracao
    protected $table = 'Sales';
    protected $primaryKey = 'id';

    // Colunas da tabela
    
    protected $id;
    protected $title;
    protected $description;
    protected $date_start;
    protected $date_end;
    protected $alternative_value;
    protected $status;
    protected $user_id;
    protected $course_id;
    protected $img_path;

}