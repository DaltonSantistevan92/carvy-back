<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/productoModel.php';
// require_once 'models/productoModel.php';

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{

    protected $table = "materiales";
    protected $fillable = ['orden_id', 'producto_id', 'comprado', 'cantidad', 'fecha_registro'];
    public $timestamps = false;

    //Muchos a uno --- uno a muchos(Inverso)
    public function orden_de_trabajos()
    {
        return $this->belongsTo(Orden::class);
    }

    //Muchos a uno --- uno a muchos(Inverso)
    public function producto()
    {
        return $this->belongsTo(Producto::class);

    }


    


}
