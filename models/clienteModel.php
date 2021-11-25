<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/personaModel.php';
require_once 'models/ventaModel.php';

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{

    protected $table = "clientes";
    protected $fillable = ['persona_id', 'fecha_ingreso', 'hora_ingreso', 'estado'];

    //Muchos a uno --- uno a muchos(Inverso)
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function venta(){
        return $this->hasMany(Venta::class);
    }
}
