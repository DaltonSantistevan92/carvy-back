<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/productoModel.php';

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{

    protected $table = "categorias";
    protected $fillable = ['categoria', 'fecha', 'estado'];

    //uno a muchos
    public function producto(){
        return $this->hasMany(Producto::class);
    }
    
}
