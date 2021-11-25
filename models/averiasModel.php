<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
//require_once 'models/personaModel.php';

use Illuminate\Database\Eloquent\Model;

class Averias extends Model
{

    protected $table = "averias_fallas";
    //protected $fillable = ['persona_id', 'fecha_registro', 'estado', 'status'];

    //Muchos
   /*  public function ordentrabajoAveriasfallas(){
        return $this->hasMany(Averias::class);
    } */
    


}
