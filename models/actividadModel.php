<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/ordenModel.php';

use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    protected $table = "actividades";
    protected $fillable = ['orden_id', 'detalle','progreso','total','faltante','fecha'];
    
    //Muchos
    public function orden(){
        return $this->belongsTo(Orden::class);
    } 
}
