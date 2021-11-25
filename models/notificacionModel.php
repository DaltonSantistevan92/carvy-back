<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/rolModel.php';

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model {
    
    protected $table = "notificaciones";
    protected $fillable = ['rol_id', 'codigo', 'titulo', 'mensaje', 'icono', 'leido'];

    //Muchos a uno --- uno a muchos(Inverso)
    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }
} 
