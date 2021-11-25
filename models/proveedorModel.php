<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
//require_once 'models/menuModel.php';


use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{

    protected $table = "proveedores";
    protected $fillable = ['ruc', 'razon_social', 'direccion', 'correo', 'fecha', 'telefono', 'estado'];

}
