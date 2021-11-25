<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/compraModel.php';
require_once 'models/productoModel.php';

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model{

    protected $table = "detalle_compra";
    protected $fillable = ['compra_id', 'producto_id', 'cantidad', 'total', 'precio_compra'];
    public $timestamps = false;

    //Muchos a uno --- uno a muchos(Inverso)
    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function producto(){
        return $this->belongsTo(Producto::class);
    }

}