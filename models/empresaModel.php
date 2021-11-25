<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/ventaModel.php';

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model{ 

    protected $table = "empresa";

    public function venta(){
        return $this->hasMany(Venta::class);
    }
}