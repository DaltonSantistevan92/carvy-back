<?php

require_once 'app/error.php';

class MecanicoAccion 
{

    public function index($metodo_http, $ruta, $params = null)
    {
        switch ($metodo_http) {

            case 'get':
                if ($ruta == '/mecanico/listar' && $params) {
                    Route::get('/mecanico/listar/:id', 'mecanicoController@buscar', $params);
                }else 
                if ($ruta == '/mecanico/listar') {
                    Route::get('/mecanico/listar', 'mecanicoController@listar'); 
                }else
                if($ruta == '/mecanico/search' && $params){
                    Route::get('/mecanico/search/:texto', 'mecanicoController@search', $params);
                }else
                if($ruta == '/mecanico/reporteorden' && $params){
                    Route::get('/mecanico/reporteorden/:inicio/:fin/:mecanico_id', 'mecanicoController@reporteOrden', $params); 
                }
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
