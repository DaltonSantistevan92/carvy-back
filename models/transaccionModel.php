<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/compraModel.php';

use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model{

    protected $table = "transacciones";
    protected $fillable = ['tipo_movimiento', 'fecha', 'descripcion', 'venta_id', 'compra_id'];

    //Muchos a uno --- uno a muchos(Inverso)
    public function compra(){
        return $this->belongsTo(Compra::class); 
    }
}