<?php

require_once 'app/error.php';

class AveriasAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {

            case 'get':
                if ($ruta == '/averias/listar' && $params) {
                    Route::get('/averias/listar/:id', 'averiasController@buscar', $params);
                }else 
                if ($ruta == '/averias/listar') {
                    Route::get('/averias/listar', 'averiasController@listar');
                }else
                // if($ruta == '/averias/orden' && $params){
                //     Route::get('/averias/orden/:id_orden', 'averiasController@getAveriasByOrden', $params);
                // }
                
                break;

            case 'post':
                /* if ($ruta == '/compra/save') {
                    Route::post('/compra/save', 'compraController@guardar');
                }
                break; */

            case 'delete':
                /*  if($params){
                if($ruta == '/categoria/eliminar'){
                Route::delete('/categoria/eliminar/:id', 'categoriaController@eliminar', $params);
                }
                }else{
                ErrorClass::e('400', 'No ha enviado parámetros por la url');
                } */
                break;
        }
    }
}
