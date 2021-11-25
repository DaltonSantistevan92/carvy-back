<?php

require_once 'app/error.php';

class DetalleCompraAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {

            case 'get':
                if ($ruta == '/detallecompra') {
                    Route::get('/detallecompra/listar', 'detallecompraController@listar');
                }
                else
                if($ruta == '/detallecompra/listar'){
                    Route::get('/detallecompra/listar', 'detallecompraController@listar');
                }
                break;

            case 'post':
               
                break;

            case 'delete':
               
                break;
        }
    }
}
