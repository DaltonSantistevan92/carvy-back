<?php

require_once 'app/error.php';

class NotificacionAccion 
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/notificacion/listar' && $params) {
                    Route::get('/notificacion/listar/:id', 'notificacionController@one', $params);
                }
                else
                if($ruta == '/notificacion/all' && $params){
                    Route::get('/notificacion/all/:cantidad', 'notificacionController@all', $params);
                }
                // if($ruta == '/categoria/grafica_stock_productos'){
                //     Route::get('/categoria/grafica_stock_productos', 'categoriaController@grafica_stock_productos');
                // }
                break;
                

            case 'post':
                if ($ruta == '/notificacion/generar') {
                    Route::post('/notificacion/generar', 'notificacionController@generar');
                }
                break;

            case 'delete':
                break; 

            case 'put':
              
            break;
        }
    }
}
