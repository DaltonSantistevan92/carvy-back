<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/ordenModel.php';

use Illuminate\Database\Eloquent\Model;

class OrdenAveriasFallas extends Model{

    protected $table = "ordentrabajo_averiasfallas";
    protected $fillable = ['orden_de_trabajo_id', 'averias_fallas_id', 'estado'];
    public $timestamps = false;

    //Muchos a uno --- uno a muchos(Inverso)
    public function orden_de_trabajos()
    {
        return $this->belongsTo(Orden::class);
    }

}