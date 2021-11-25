<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/ordenModel.php';
require_once 'models/ventaModel.php'; 


use Illuminate\Database\Eloquent\Model;

class Servicio extends Model{

    protected $table = "servicios";
    protected $filleable = ['orden_id', 'venta_id', 'suma', 'fecha'];

    public function orden(){
        return $this->belongsTo(Orden::class);
    }

    public function venta(){
        return $this->belongsTo(Venta::class); 
    }

   

    
}