<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/proveedorModel.php';
require_once 'models/usuarioModel.php';
require_once 'models/detallecompraModel.php';

use Illuminate\Database\Eloquent\Model;


class Compra extends Model
{

    protected $table = "compras";
    protected $fillable = ['proveedor_id', 'usuario_id', 'serie_documento', 'descuento', 'sub_total', 'iva', 'total', 'estado', 'fecha_compra'];

    //Muchos a uno --- uno a muchos(Inverso)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    //Muchos a uno --- uno a muchos(Inverso)
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    //uno a muchos
    public function detalle_compra(){
        return $this->hasMany(DetalleCompra::class);
    }
}
