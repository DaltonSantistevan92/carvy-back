<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/ventaModel.php';
require_once 'models/productoModel.php'; 


use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model{

    protected $table = "detalle_venta";
    protected $fillable = ['venta_id','cantidad', 'precio_venta', 'total', 'producto_id', 'es_orden'];
    public $timestamps = false;

    //Muchos a uno --- uno a muchos(Inverso)
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
 
        //Muchos a uno --- uno a muchos(Inverso)
    public function producto(){
        return $this->belongsTo(Producto::class);
    }

    

    

}