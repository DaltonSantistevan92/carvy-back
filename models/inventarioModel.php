<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/productoModel.php';
require_once 'models/transaccionModel.php';

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model{

    protected $table = "inventario"; 
    protected $fillable = ['producto_id', 'transaccion_id', 'tipo','cantidad', 'precio', 'total', 'cantidad_disponible', 'precio_disponible', 'total_disponible','fecha'];

    
    //Muchos a uno --- uno a muchos(Inverso)
    public function producto(){
        return $this->belongsTo(Producto::class);
    }

    
    //Muchos a uno --- uno a muchos(Inverso)
    public function transaccion(){
        return $this->belongsTo(Transaccion::class); 
    }
} 